<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\DogGoodsOrder;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(string $token)
    {
        $order = DogGoodsOrder::with(['item', 'user'])
            ->where('payment_token', $token)
            ->firstOrFail();

        if ($order->payment_status === PaymentStatus::Paid) {
            return view('payment.completed', compact('order'));
        }

        if ($order->payment_status === PaymentStatus::Expired) {
            return view('payment.expired', compact('order'));
        }

        // 送信から7日経過で期限切れ
        if ($order->payment_sent_at && $order->payment_sent_at->diffInDays(now()) >= 7) {
            $order->update(['payment_status' => PaymentStatus::Expired]);
            return view('payment.expired', compact('order'));
        }

        $item = $order->item;
        $opts = $order->custom_options ?? [];

        return view('payment.show', compact('order', 'item', 'opts'));
    }

    public function complete(Request $request, string $token)
    {
        $order = DogGoodsOrder::where('payment_token', $token)->firstOrFail();

        abort_if($order->payment_status === PaymentStatus::Paid, 409);
        abort_if($order->payment_status === PaymentStatus::Expired, 410);

        // 決済処理（今後Stripe等と連携）
        $order->update([
            'payment_status' => PaymentStatus::Paid,
            'order_status'   => OrderStatus::Paid,
        ]);

        return view('payment.completed', compact('order'));
    }
}
