@extends('layouts.app')
@section('title', 'お支払い | INU GOODS')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl">

    <div class="text-center mb-10">
        <span class="text-orange-500 font-bold tracking-widest uppercase text-xs">Payment</span>
        <h1 class="text-3xl font-black text-slate-900 mt-2">お支払い</h1>
        <p class="text-slate-400 text-sm mt-2">注文番号 #{{ $order->id }}</p>
    </div>

    {{-- 商品情報 --}}
    <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm p-6 mb-6">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ご注文内容</p>
        <div class="flex items-center gap-4 mb-4">
            @if($item->thumbnail_image)
                <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-16 h-16 rounded-2xl object-cover">
            @else
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl">🏷️</div>
            @endif
            <div>
                <p class="font-bold text-slate-900">{{ $item->name }}</p>
                @if(!empty($opts['name']))
                    <p class="text-sm text-slate-400">{{ $opts['name'] }} / {{ $opts['breed'] ?? '' }}</p>
                @endif
            </div>
        </div>

        @if($order->processed_image)
            <div class="rounded-2xl overflow-hidden mb-4">
                <img src="{{ asset('storage/' . $order->processed_image) }}" class="w-full object-cover" alt="仕上がりプレビュー">
            </div>
        @endif

        <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between">
            <span class="text-slate-600 font-bold">お支払い金額</span>
            <span class="text-2xl font-black text-slate-900">¥{{ number_format($item->price) }}<span class="text-sm font-medium text-slate-400">（税込）</span></span>
        </div>
    </div>

    {{-- 配送先 --}}
    <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm p-6 mb-6">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">配送先</p>
        <div class="text-sm text-slate-700 space-y-1">
            <p class="font-bold">{{ $order->shipping_name ?? '' }}</p>
            @php $postal = preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', preg_replace('/[^0-9]/', '', $order->postal_code ?? '')); @endphp
            <p>〒{{ $postal }}</p>
            <p>{{ ($order->prefecture ?? '') . ($order->city ?? '') }} {{ $order->address_line ?? '' }}</p>
            <p class="text-slate-400">📞 {{ $order->phone ?? '' }}</p>
        </div>
    </div>

    {{-- 注意事項 --}}
    <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 mb-8 text-sm text-orange-700">
        ⏰ このページの有効期限：<strong>{{ $order->payment_sent_at?->addDays(7)->format('Y年m月d日') ?? '—' }}</strong><br>
        期限を過ぎると無効になります。お早めにお手続きください。
    </div>

    {{-- 決済ボタン --}}
    <form method="POST" action="{{ route('payment.complete', $order->payment_token) }}">
        @csrf
        <button type="submit"
                class="w-full bg-orange-500 text-white py-5 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100">
            ¥{{ number_format($item->price) }} を支払う →
        </button>
    </form>

</div>
@endsection
