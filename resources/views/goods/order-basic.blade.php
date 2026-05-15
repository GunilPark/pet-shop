@extends('layouts.app')
@section('title', '商品購入 | INU GOODS')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl">
    <div class="mb-8">
        <a href="/goods" class="text-slate-400 text-sm hover:text-orange-500 transition">← グッズ一覧に戻る</a>
    </div>

    {{-- 商品情報 --}}
    <div class="bg-slate-50 rounded-[32px] p-6 mb-8 flex gap-5 items-center border border-slate-100">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-20 h-20 rounded-2xl object-cover">
        @else
            <div class="w-20 h-20 rounded-2xl bg-slate-200 flex items-center justify-center text-3xl">🐾</div>
        @endif
        <div>
            <p class="text-xs text-slate-400 font-bold uppercase">Basic Item</p>
            <h2 class="text-xl font-black text-slate-900">{{ $item->name }}</h2>
            <p class="text-orange-500 font-black text-lg">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('goods.order.preview', $item) }}">
            @csrf

            {{-- 数量 --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">数量</p>
                <select name="quantity" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('quantity', $saved['quantity'] ?? 1) == $i ? 'selected' : '' }}>{{ $i }}個</option>
                    @endfor
                </select>
            </div>

            <hr class="border-slate-100 mb-8">

            {{-- 住所セクション --}}
            @include('goods._address_section', ['user' => $user, 'saved' => $saved])

            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-500 transition shadow-lg">
                確認画面へ進む →
            </button>
        </form>
    </div>
</div>
@endsection
