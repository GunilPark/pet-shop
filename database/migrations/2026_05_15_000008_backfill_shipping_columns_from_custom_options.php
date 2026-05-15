<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('dog_goods_orders')
            ->whereNull('deleted_at')
            ->whereNull('shipping_name')
            ->get(['id', 'custom_options'])
            ->each(function ($order) {
                $opts = json_decode($order->custom_options, true) ?? [];

                if (empty($opts)) return;

                // 住所・数量を専用カラムへ移行
                DB::table('dog_goods_orders')->where('id', $order->id)->update([
                    'quantity'      => (int) ($opts['quantity'] ?? 1),
                    'shipping_name' => $opts['shipping_name'] ?? null,
                    'postal_code'   => $opts['postal_code'] ?? null,
                    'prefecture'    => $opts['prefecture'] ?? null,
                    'city'          => $opts['city'] ?? null,
                    'address_line'  => $opts['address_line'] ?? null,
                    'phone'         => $opts['phone'] ?? null,
                ]);

                // custom_options から住所・数量キーを除去
                $shippingKeys = ['quantity', 'shipping_name', 'postal_code', 'prefecture', 'city', 'address_line', 'phone'];
                $cleaned = array_diff_key($opts, array_flip($shippingKeys));

                DB::table('dog_goods_orders')->where('id', $order->id)->update([
                    'custom_options' => json_encode($cleaned, JSON_UNESCAPED_UNICODE),
                ]);
            });
    }

    public function down(): void
    {
        // ロールバック時は custom_options に住所を書き戻す
        DB::table('dog_goods_orders')
            ->whereNull('deleted_at')
            ->get(['id', 'custom_options', 'quantity', 'shipping_name', 'postal_code', 'prefecture', 'city', 'address_line', 'phone'])
            ->each(function ($order) {
                $opts = json_decode($order->custom_options, true) ?? [];

                $opts['quantity']      = $order->quantity;
                $opts['shipping_name'] = $order->shipping_name;
                $opts['postal_code']   = $order->postal_code;
                $opts['prefecture']    = $order->prefecture;
                $opts['city']          = $order->city;
                $opts['address_line']  = $order->address_line;
                $opts['phone']         = $order->phone;

                DB::table('dog_goods_orders')->where('id', $order->id)->update([
                    'custom_options' => json_encode($opts, JSON_UNESCAPED_UNICODE),
                ]);
            });
    }
};
