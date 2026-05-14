@extends('layouts.app')
@section('title', '注文確認・プレビュー | GUNIL PET SHOP')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-2xl">

    <h1 class="text-2xl font-black text-slate-900 mb-2">注文確認 / プレビュー</h1>
    <p class="text-slate-400 text-sm mb-10">内容をご確認の上、「購入を確定する」または「メールで相談する」をお選びください。</p>

    {{-- 商品情報 --}}
    <div class="bg-slate-50 rounded-[24px] p-5 mb-8 flex gap-4 items-center border border-slate-100">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-16 h-16 rounded-xl object-cover">
        @else
            <div class="w-16 h-16 rounded-xl bg-slate-200 flex items-center justify-center text-2xl">🐾</div>
        @endif
        <div>
            <p class="text-xs text-slate-400 font-bold">{{ $item->product_type->getLabel() }}</p>
            <h2 class="font-black text-slate-900">{{ $item->name }}</h2>
            <p class="text-orange-500 font-black">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    {{-- プレビューエリア --}}
    @if($item->product_type->value === 'nose_print')
        <div class="mb-10">
            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">タグ プレビュー</h3>
            <div class="flex gap-8 justify-center flex-wrap">

                {{-- 表面 --}}
                <div class="text-center">
                    <p class="text-xs text-slate-400 mb-3 font-bold">表面（鼻紋）</p>
                    @if(isset($data['temp_image']))
                        @if($data['tag_shape'] === 'round')
                            <div class="w-40 h-40 rounded-full border-4 border-slate-800 bg-white shadow-xl flex items-center justify-center overflow-hidden mx-auto">
                                <img src="{{ asset('storage/' . $data['temp_image']) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-64 h-28 rounded-2xl border-4 border-slate-800 bg-white shadow-xl flex items-center justify-center overflow-hidden mx-auto">
                                <img src="{{ asset('storage/' . $data['temp_image']) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                    @else
                        <div class="{{ $data['tag_shape'] === 'round' ? 'w-40 h-40 rounded-full' : 'w-64 h-28 rounded-2xl' }} border-4 border-slate-800 bg-slate-100 flex items-center justify-center mx-auto text-3xl shadow-xl">
                            🐽
                        </div>
                    @endif
                </div>

                {{-- 裏面 --}}
                <div class="text-center">
                    <p class="text-xs text-slate-400 mb-3 font-bold">裏面（情報）</p>
                    <div class="{{ $data['tag_shape'] === 'round' ? 'w-40 h-40 rounded-full' : 'w-64 h-28 rounded-2xl' }} border-4 border-slate-800 bg-slate-900 text-white flex flex-col items-center justify-center mx-auto shadow-xl p-4">
                        <p class="font-black text-lg">{{ $data['back_name'] }}</p>
                        @if(!empty($data['back_breed'])) <p class="text-xs opacity-70">{{ $data['back_breed'] }}</p> @endif
                        @if(!empty($data['back_birthday'])) <p class="text-xs opacity-70">{{ $data['back_birthday'] }}</p> @endif
                        @if(!empty($data['back_message'])) <p class="text-xs opacity-50 mt-1 italic">{{ $data['back_message'] }}</p> @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif($item->product_type->value === 'silhouette')
        <div class="mb-10">
            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">シルエット プレビュー</h3>

            {{-- 商品に犬の輪郭が乗るイメージ --}}
            <div class="relative bg-slate-100 rounded-[32px] overflow-hidden shadow-xl aspect-video flex items-center justify-center">
                {{-- 商品背景（サムネイル） --}}
                @if($item->thumbnail_image)
                    <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="absolute inset-0 w-full h-full object-cover opacity-30">
                @endif

                {{-- シルエット表示 --}}
                <div class="relative z-10 text-center">
                    @php
                        $useProfileImage = $data['use_profile_image'] ?? 'yes';
                        $imgUrl = null;
                        if ($useProfileImage === 'yes' && $dogProfile?->profile_image) {
                            $imgUrl = asset('storage/' . $dogProfile->profile_image);
                        } elseif (isset($data['temp_image'])) {
                            $imgUrl = asset('storage/' . $data['temp_image']);
                        }
                        $useProfileText = $data['use_profile_text'] ?? 'yes';
                        $displayName  = $useProfileText === 'yes' ? $dogProfile?->name : ($data['custom_name'] ?? '');
                        $displayBreed = $useProfileText === 'yes' ? $dogProfile?->breed : ($data['custom_breed'] ?? '');
                    @endphp

                    @if($imgUrl)
                        <div class="w-36 h-36 rounded-full overflow-hidden mx-auto shadow-2xl border-4 border-white"
                             style="filter: contrast(200%) grayscale(100%);">
                            <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-36 h-36 rounded-full bg-slate-400 mx-auto flex items-center justify-center text-5xl shadow-2xl border-4 border-white">
                            🐕
                        </div>
                    @endif

                    @if($displayName)
                        <p class="font-black text-xl text-slate-900 mt-3 drop-shadow-lg">{{ $displayName }}</p>
                    @endif
                    @if($displayBreed)
                        <p class="text-sm text-slate-600">{{ $displayBreed }}</p>
                    @endif
                    @if(!empty($data['logo_text']))
                        <p class="text-xs text-orange-500 font-bold mt-1 tracking-widest uppercase">{{ $data['logo_text'] }}</p>
                    @endif
                </div>

                <div class="absolute bottom-4 left-0 right-0 text-center">
                    <p class="text-xs text-slate-400 bg-white/80 inline-block px-3 py-1 rounded-full">※ 実際の仕上がりイメージです。細部は加工後にご確認いただけます。</p>
                </div>
            </div>
        </div>

    @else
        {{-- 基本商品 --}}
        <div class="bg-slate-50 rounded-2xl p-6 mb-10">
            <p class="text-sm text-slate-600">数量: <span class="font-bold text-slate-900">{{ $data['quantity'] ?? 1 }}個</span></p>
        </div>
    @endif

    {{-- 入力内容確認 --}}
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-8 mb-8">
        <h3 class="font-bold text-slate-700 mb-4">入力内容の確認</h3>
        <dl class="space-y-2 text-sm">
            @foreach($data as $key => $val)
                @if(!in_array($key, ['temp_image']) && !empty($val))
                    <div class="flex gap-3">
                        <dt class="text-slate-400 w-40 flex-shrink-0">{{ $key }}</dt>
                        <dd class="text-slate-900 font-medium">{{ is_string($val) ? $val : '' }}</dd>
                    </div>
                @endif
            @endforeach
        </dl>
    </div>

    {{-- アクションボタン --}}
    <form method="POST" action="{{ route('goods.order.store', $item) }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <button type="submit" name="action" value="order"
                class="bg-orange-500 text-white py-5 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100 text-center">
                ✅ 購入を確定する
            </button>
            <button type="submit" name="action" value="consult"
                class="bg-slate-100 text-slate-700 py-5 rounded-2xl font-bold text-lg hover:bg-slate-200 transition text-center">
                📧 メールで相談する
            </button>
        </div>
        <p class="text-xs text-slate-400 text-center mt-4">「メールで相談する」を選ぶと、担当者がご要望をうかがいます。</p>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('goods.order.create', $item) }}" class="text-slate-400 text-sm hover:text-orange-500 transition">← フォームに戻って修正する</a>
    </div>

</div>
@endsection
