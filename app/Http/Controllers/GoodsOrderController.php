<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProcessingStatus;
use App\Enums\ProductType;
use App\Mail\ConsultationMail;
use App\Mail\NewOrderAdminMail;
use App\Mail\NewOrderUserMail;
use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GoodsOrderController extends Controller
{
    public function create(DogGoodsItem $item, Request $request)
    {
        abort_if(! $item->is_active, 404);

        $saved = $request->session()->get('order_preview', []);
        $user  = auth()->user();
        $dogProfiles = $user->dogProfiles()->active()->get();

        return match($item->product_type) {
            ProductType::NameTag => view('goods.order-name-tag', compact('item', 'saved', 'user', 'dogProfiles')),
            default              => view('goods.order-basic', compact('item', 'saved', 'user', 'dogProfiles')),
        };
    }

    public function preview(Request $request, DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $data = match($item->product_type) {
            ProductType::NameTag => $this->validateNameTag($request),
            default              => $this->validateBasic($request),
        };

        $engravingType = $data['engraving_type'] ?? 'silhouette';

        if ($request->hasFile('uploaded_image')) {
            // ファイルアップロード
            $data['temp_image'] = $this->processImage(
                $this->gdFromUpload($request->file('uploaded_image')),
                $engravingType
            );
        } elseif (! empty($data['captured_image'])) {
            // カメラキャプチャ（base64）
            // フロント側で既にマスク適用済みなのでエングレービング処理のみ
            $gdImg = $this->gdFromBase64($data['captured_image']);
            if ($gdImg) {
                $data['temp_image'] = $this->processImage($gdImg, $engravingType, masked: true);
            }
        }

        unset($data['uploaded_image'], $data['captured_image']);

        $request->session()->put('order_preview', $data);

        return view('goods.order-preview', compact('item', 'data'));
    }

    public function store(Request $request, DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $data = $request->session()->get('order_preview');
        if (! $data) {
            return redirect()->route('goods.order.create', $item)->with('error', 'セッションが切れました。もう一度入力してください。');
        }

        $isConsultation = $request->input('action') === 'consult';

        $uploadedImage = $data['temp_image'] ?? null;
        if ($uploadedImage) {
            $final = str_replace('orders/temp/', 'orders/uploaded/', $uploadedImage);
            Storage::disk('public')->move($uploadedImage, $final);
            $uploadedImage = $final;
        }

        // 相談・購入どちらも注文レコードを作成（決済は今後実装予定）
        $order = DogGoodsOrder::create([
            'user_id'           => auth()->id(),
            'dog_profile_id'    => ($data['dog_profile_id'] ?? '') !== '' ? $data['dog_profile_id'] : null,
            'item_id'           => $item->id,
            'order_status'      => OrderStatus::Pending,
            'processing_status' => $isConsultation ? ProcessingStatus::Reviewing : ProcessingStatus::Confirmed,
            'payment_status'    => PaymentStatus::Unsent,
            'uploaded_image'    => $uploadedImage,
            'custom_options'    => $data,
            'is_consultation'   => $isConsultation,
            'ordered_at'        => now(),
        ]);

        $request->session()->forget('order_preview');

        $adminEmail = config('mail.from.address');

        if ($isConsultation) {
            // 管理者へ相談申請メール
            Mail::to($adminEmail)->send(new ConsultationMail($order, $item));
            return redirect()->route('mypage')->with('success', 'ご相談を受け付けました！注文も登録されました。担当者よりご連絡いたします。');
        }

        // 管理者へ新規注文通知 + ユーザーへ受付完了メール
        Mail::to($adminEmail)->send(new NewOrderAdminMail($order, $item));
        Mail::to($order->user->email)->send(new NewOrderUserMail($order, $item));

        return redirect()->route('mypage')->with('success', 'ご注文が完了しました！受付完了メールをお送りしました。');
    }

    // ---- バリデーション ----

    private function validateBasic(Request $request): array
    {
        return $request->validate([
            'quantity'     => ['required', 'integer', 'min:1', 'max:10'],
            'postal_code'  => ['required', 'string', 'max:8'],
            'prefecture'   => ['required', 'string', 'max:20'],
            'city'         => ['required', 'string', 'max:100'],
            'address_line' => ['required', 'string', 'max:200'],
            'phone'        => ['required', 'string', 'max:20'],
            'shipping_name'=> ['required', 'string', 'max:100'],
        ]);
    }

    private function validateNameTag(Request $request): array
    {
        $request->validate([
            'material'        => ['required', 'in:black,wood'],
            'engraving_type'  => ['required', 'in:nose_print,silhouette'],
            'uploaded_image'  => ['nullable', 'image', 'max:10240'],
            'captured_image'  => ['nullable', 'string'],
            'name'            => ['required', 'string', 'max:50'],
            'breed'           => ['nullable', 'string', 'max:50'],
            'birthday'        => ['nullable', 'string', 'max:20'],
            'message'         => ['nullable', 'string', 'max:100'],
            'postal_code'     => ['required', 'string', 'max:8'],
            'prefecture'      => ['required', 'string', 'max:20'],
            'city'            => ['required', 'string', 'max:100'],
            'address_line'    => ['required', 'string', 'max:200'],
            'phone'           => ['required', 'string', 'max:20'],
            'shipping_name'   => ['required', 'string', 'max:100'],
            'dog_profile_id'  => ['nullable', 'integer'],
        ]);

        // 写真は uploaded_image か captured_image のどちらか必須
        if (! $request->hasFile('uploaded_image') && empty($request->input('captured_image'))) {
            return back()->withErrors(['uploaded_image' => '写真を撮影またはアップロードしてください。'])->withInput()->throwResponse();
        }

        return $request->only(['material', 'engraving_type', 'uploaded_image', 'captured_image', 'name', 'breed', 'birthday', 'message', 'postal_code', 'prefecture', 'city', 'address_line', 'phone', 'shipping_name', 'dog_profile_id']);
    }

    // ---- 画像ロード ----

    private function gdFromUpload(UploadedFile $file): \GdImage|false
    {
        $mime = $file->getMimeType();
        return match(true) {
            str_contains($mime, 'png')  => imagecreatefrompng($file->getRealPath()),
            str_contains($mime, 'webp') => imagecreatefromwebp($file->getRealPath()),
            default                     => imagecreatefromjpeg($file->getRealPath()),
        };
    }

    private function gdFromBase64(string $base64): \GdImage|false
    {
        // "data:image/jpeg;base64,..." 形式から純粋なデータを取り出す
        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64, 2)[1];
        }
        $binary = base64_decode($base64);
        if (! $binary) return false;
        return imagecreatefromstring($binary);
    }

    // ---- 画像処理：マスク適用 → エングレービング変換 ----

    private function processImage(\GdImage|false $src, string $engravingType, bool $masked = false): string
    {
        if (! $src) {
            return '';
        }

        $w = imagesx($src);
        $h = imagesy($src);

        // リサイズ（最大800px で高速化）
        $maxSize = 800;
        if ($w > $maxSize || $h > $maxSize) {
            $ratio   = min($maxSize / $w, $maxSize / $h);
            $nw      = (int) ($w * $ratio);
            $nh      = (int) ($h * $ratio);
            $resized = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($src);
            $src = $resized;
            $w = $nw;
            $h = $nh;
        }

        // サーバー側マスク適用（ファイルアップロード時のみ、カメラはフロント適用済み）
        if (! $masked) {
            $src = $this->applyGuideMask($src, $w, $h, $engravingType);
        }

        // グレースケール
        imagefilter($src, IMG_FILTER_GRAYSCALE);

        // コントラスト強調
        imagefilter($src, IMG_FILTER_CONTRAST, -70);

        // 色反転（黒い被写体 → 白い刻印）
        imagefilter($src, IMG_FILTER_NEGATE);

        // 暗すぎるピクセル（元・明るい背景）を純黒に
        $threshold = $engravingType === 'nose_print' ? 55 : 45;
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $rgb = imagecolorat($src, $x, $y);
                if ((($rgb >> 16) & 0xFF) < $threshold) {
                    imagesetpixel($src, $x, $y, 0x000000);
                }
            }
        }

        imagefilter($src, IMG_FILTER_SMOOTH, 1);

        $dir = storage_path('app/public/orders/temp');
        if (! is_dir($dir)) mkdir($dir, 0755, true);

        $filename = uniqid('eng_') . '.jpg';
        imagejpeg($src, $dir . '/' . $filename, 90);
        imagedestroy($src);

        return 'orders/temp/' . $filename;
    }

    // ---- ガイド枠マスク（枠外を黒塗り） ----
    // フロントのSVG座標（鼻: cx=50% cy=50% rx=35% ry=28% / シルエット: x=10% y=15% w=80% h=70%）と一致

    private function applyGuideMask(\GdImage $src, int $w, int $h, string $engravingType): \GdImage
    {
        $black = imagecolorallocate($src, 0, 0, 0);

        if ($engravingType === 'nose_print') {
            $cx = (int) ($w * 0.50);
            $cy = (int) ($h * 0.50);
            $rx = (int) ($w * 0.35);
            $ry = (int) ($h * 0.28);

            for ($y = 0; $y < $h; $y++) {
                for ($x = 0; $x < $w; $x++) {
                    $dx = ($x - $cx) / $rx;
                    $dy = ($y - $cy) / $ry;
                    if (($dx * $dx + $dy * $dy) > 1.0) {
                        imagesetpixel($src, $x, $y, $black);
                    }
                }
            }
        } else {
            // シルエット：長方形マスク
            $mx = (int) ($w * 0.10);
            $my = (int) ($h * 0.15);
            $mw = (int) ($w * 0.80);
            $mh = (int) ($h * 0.70);

            // 上
            imagefilledrectangle($src, 0, 0, $w - 1, $my - 1, $black);
            // 下
            imagefilledrectangle($src, 0, $my + $mh, $w - 1, $h - 1, $black);
            // 左
            imagefilledrectangle($src, 0, $my, $mx - 1, $my + $mh - 1, $black);
            // 右
            imagefilledrectangle($src, $mx + $mw, $my, $w - 1, $my + $mh - 1, $black);
        }

        return $src;
    }

}
