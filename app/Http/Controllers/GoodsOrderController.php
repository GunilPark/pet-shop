<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\ProcessingStatus;
use App\Enums\ProductType;
use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GoodsOrderController extends Controller
{
    // ---- フォーム表示 ----

    public function create(DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        return match($item->product_type) {
            ProductType::NameTag => view('goods.order-name-tag', compact('item')),
            default              => view('goods.order-basic', compact('item')),
        };
    }

    // ---- 確認画面（セッションに保存して表示）----

    public function preview(Request $request, DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $data = match($item->product_type) {
            ProductType::NameTag => $this->validateNameTag($request),
            default              => $this->validateBasic($request),
        };

        // 画像をアップロード＋エングレービング風に加工
        if ($request->hasFile('uploaded_image')) {
            $data['temp_image'] = $this->processAndStore($request->file('uploaded_image'));
        }

        // UploadedFile オブジェクトはセッションに入れない
        unset($data['uploaded_image']);

        $request->session()->put('order_preview', $data);

        return view('goods.order-preview', compact('item', 'data'));
    }

    // ---- 注文確定 or 相談申請 ----

    public function store(Request $request, DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $data = $request->session()->pull('order_preview');
        abort_if(! $data, 400);

        $isConsultation = $request->input('action') === 'consult';

        $uploadedImage = $data['temp_image'] ?? null;
        if ($uploadedImage) {
            $final = str_replace('orders/temp/', 'orders/uploaded/', $uploadedImage);
            Storage::disk('public')->move($uploadedImage, $final);
            $uploadedImage = $final;
        }

        $order = DogGoodsOrder::create([
            'user_id'           => auth()->id(),
            'dog_profile_id'    => null,
            'item_id'           => $item->id,
            'order_status'      => OrderStatus::Pending,
            'processing_status' => ProcessingStatus::Pending,
            'uploaded_image'    => $uploadedImage,
            'custom_options'    => $data,
            'is_consultation'   => $isConsultation,
            'ordered_at'        => now(),
        ]);

        if ($isConsultation) {
            $this->sendConsultationMail($order, $item);
            return redirect()->route('mypage')->with('success', 'ご相談を受け付けました！担当者よりメールでご連絡いたします。');
        }

        return redirect()->route('mypage')->with('success', 'ご注文が完了しました！管理者確認後、加工を開始します。');
    }

    // ---- バリデーション ----

    private function validateBasic(Request $request): array
    {
        return $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);
    }

    private function validateNameTag(Request $request): array
    {
        return $request->validate([
            'material'       => ['required', 'in:black,wood'],
            'engraving_type' => ['required', 'in:nose_print,silhouette'],
            'uploaded_image' => ['required', 'image', 'max:10240'],
            'name'           => ['required', 'string', 'max:50'],
            'breed'          => ['nullable', 'string', 'max:50'],
            'birthday'       => ['nullable', 'string', 'max:20'],
            'message'        => ['nullable', 'string', 'max:100'],
        ]);
    }

    // ---- 画像処理：エングレービング風に変換 ----

    private function processAndStore(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        $src = match(true) {
            str_contains($mime, 'png')  => imagecreatefrompng($file->getRealPath()),
            str_contains($mime, 'webp') => imagecreatefromwebp($file->getRealPath()),
            default                     => imagecreatefromjpeg($file->getRealPath()),
        };

        if (! $src) {
            // 加工失敗時はそのまま保存
            return $file->store('orders/temp', 'public');
        }

        $w = imagesx($src);
        $h = imagesy($src);

        // グレースケール変換
        imagefilter($src, IMG_FILTER_GRAYSCALE);

        // コントラスト強調（-100〜100、マイナスほど強）
        imagefilter($src, IMG_FILTER_CONTRAST, -60);

        // 明度を少し上げる
        imagefilter($src, IMG_FILTER_BRIGHTNESS, 15);

        // スムーズ
        imagefilter($src, IMG_FILTER_SMOOTH, 2);

        $dir  = storage_path('app/public/orders/temp');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = uniqid('eng_') . '.jpg';
        $fullPath = $dir . '/' . $filename;
        imagejpeg($src, $fullPath, 90);
        imagedestroy($src);

        return 'orders/temp/' . $filename;
    }

    // ---- メール送信 ----

    private function sendConsultationMail(DogGoodsOrder $order, DogGoodsItem $item): void
    {
        $adminEmail = config('mail.from.address', 'admin@example.com');

        \Illuminate\Support\Facades\Mail::raw(
            "【相談申請】注文ID: {$order->id}\n商品: {$item->name}\nユーザーID: {$order->user_id}\n\nオプション内容:\n" .
            json_encode($order->custom_options, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            function ($msg) use ($adminEmail, $order) {
                $msg->to($adminEmail)
                    ->subject("【相談申請】注文#{$order->id} — " . auth()->user()->name);
            }
        );
    }
}
