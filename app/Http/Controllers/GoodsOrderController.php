<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\ProcessingStatus;
use App\Enums\ProductType;
use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GoodsOrderController extends Controller
{
    // ---- フォーム表示 ----

    public function create(DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $dogProfiles = auth()->user()->dogProfiles()->where('is_active', true)->get();

        return match($item->product_type) {
            ProductType::NosePrint  => view('goods.order-nose-print', compact('item')),
            ProductType::Silhouette => view('goods.order-silhouette', compact('item', 'dogProfiles')),
            default                 => view('goods.order-basic', compact('item')),
        };
    }

    // ---- 確認画面（セッションに保存して表示）----

    public function preview(Request $request, DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $data = match($item->product_type) {
            ProductType::NosePrint  => $this->validateNosePrint($request),
            ProductType::Silhouette => $this->validateSilhouette($request, $item),
            default                 => $this->validateBasic($request),
        };

        // 画像をセッション用に一時保存
        if ($request->hasFile('uploaded_image')) {
            $path = $request->file('uploaded_image')->store('orders/temp', 'public');
            $data['temp_image'] = $path;
        }

        $request->session()->put('order_preview', $data);

        $dogProfile = isset($data['dog_profile_id'])
            ? auth()->user()->dogProfiles()->find($data['dog_profile_id'])
            : null;

        return view('goods.order-preview', compact('item', 'data', 'dogProfile'));
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
            \Illuminate\Support\Facades\Storage::disk('public')->move($uploadedImage, $final);
            $uploadedImage = $final;
        }

        $order = DogGoodsOrder::create([
            'user_id'           => auth()->id(),
            'dog_profile_id'    => $data['dog_profile_id'] ?? null,
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

    private function validateNosePrint(Request $request): array
    {
        return $request->validate([
            'tag_shape'      => ['required', 'in:straight,round'],
            'uploaded_image' => ['required', 'image', 'max:10240'],
            'back_name'      => ['required', 'string', 'max:50'],
            'back_breed'     => ['nullable', 'string', 'max:50'],
            'back_birthday'  => ['nullable', 'string', 'max:20'],
            'back_message'   => ['nullable', 'string', 'max:100'],
        ]);
    }

    private function validateSilhouette(Request $request, DogGoodsItem $item): array
    {
        $data = $request->validate([
            'dog_profile_id'   => ['required', 'exists:dog_profiles,id'],
            'use_profile_image'=> ['required', 'in:yes,no'],
            'uploaded_image'   => ['nullable', 'required_if:use_profile_image,no', 'image', 'max:10240'],
            'use_profile_text' => ['required', 'in:yes,no'],
            'custom_name'      => ['nullable', 'required_if:use_profile_text,no', 'string', 'max:50'],
            'custom_breed'     => ['nullable', 'string', 'max:50'],
            'custom_birthday'  => ['nullable', 'string', 'max:20'],
            'logo_text'        => ['nullable', 'string', 'max:30'],
        ]);

        return $data;
    }

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
