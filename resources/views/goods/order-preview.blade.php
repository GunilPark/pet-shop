@extends('layouts.app')
@section('title', '注文確認・プレビュー | GUNIL PET SHOP')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-2xl">

    <h1 class="text-2xl font-black text-slate-900 mb-2">注文確認 / プレビュー</h1>
    <p class="text-slate-400 text-sm mb-10">仕上がりイメージをご確認の上、「購入を確定する」または「メールで相談する」をお選びください。</p>

    {{-- 商品情報 --}}
    <div class="bg-slate-50 rounded-[24px] p-5 mb-10 flex gap-4 items-center border border-slate-100">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-16 h-16 rounded-xl object-cover">
        @else
            <div class="w-16 h-16 rounded-xl bg-slate-200 flex items-center justify-center text-2xl">🏷️</div>
        @endif
        <div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-slate-200 text-slate-600">{{ $item->product_type->getLabel() }}</span>
            <h2 class="font-black text-slate-900 mt-1">{{ $item->name }}</h2>
            <p class="text-orange-500 font-black">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    {{-- ======== ネームタグ プレビュー ======== --}}
    @if($item->product_type->value === 'name_tag')

        @php
            $material     = $data['material'] ?? 'black';
            $engravingType = $data['engraving_type'] ?? 'nose_print';
            $name         = strtoupper($data['name'] ?? '');
            $breed        = $data['breed'] ?? '';
            $birthday     = $data['birthday'] ?? '';
            $message      = $data['message'] ?? '';
            $imgUrl       = isset($data['temp_image']) ? asset('storage/' . $data['temp_image']) : null;
            $isWood       = $material === 'wood';
        @endphp

        <div class="mb-10">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 text-center">— TAG PREVIEW —</h3>
            <p class="text-center text-xs mb-6"
               style="color: {{ $isWood ? '#92400e' : '#64748b' }}">
                {{ $isWood ? '木製ウッドキーホルダー' : '黒メタル軍番タグ' }} ／
                {{ $engravingType === 'nose_print' ? '鼻紋刻印' : 'シルエット刻印' }}
            </p>

            <div class="flex justify-center gap-12 flex-wrap">

                {{-- 表面 --}}
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-400 mb-3">表 面</p>

                    @if($isWood)
                        {{-- 木製キーホルダー --}}
                        <div class="mx-auto mb-1 w-7 h-7 rounded-full border-4 border-gray-400"
                             style="box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);"></div>
                        <div class="relative mx-auto shadow-2xl flex flex-col items-center justify-center overflow-hidden"
                             style="width:100px; height:148px; border-radius:6px;
                                    background-image:
                                        repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(150,100,50,0.08) 3px, rgba(150,100,50,0.08) 4px),
                                        linear-gradient(160deg, #e8c9a0 0%, #d4a96a 40%, #c49a5a 70%, #b8874a 100%);">
                            @if($imgUrl)
                                <div class="w-full" style="height:90px;">
                                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover object-top">
                                </div>
                            @else
                                <div class="flex items-center justify-center text-3xl" style="height:90px; opacity:0.4;">🐕</div>
                            @endif
                            @if($name)
                                <p class="font-black text-center tracking-widest"
                                   style="font-size:12px; color:rgba(80,40,10,0.9); letter-spacing:3px; padding:4px 6px;">{{ $name }}</p>
                            @endif
                        </div>

                    @else
                        {{-- 黒メタル軍番タグ --}}
                        <div class="relative mx-auto bg-gray-900 shadow-2xl flex flex-col items-center justify-center"
                             style="width:120px; height:168px; border-radius:12px 12px 24px 24px;">
                            <div class="absolute bg-white rounded-full border-2 border-gray-700"
                                 style="width:10px; height:10px; top:8px; left:50%; transform:translateX(-50%);"></div>
                            <div class="flex flex-col items-center justify-center w-full h-full px-3 pt-5 pb-3">
                                @if($imgUrl)
                                    <div class="w-full flex-1 overflow-hidden rounded-sm mb-1">
                                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-gray-700 rounded-sm flex items-center justify-center text-3xl mb-1">
                                        {{ $engravingType === 'nose_print' ? '🐽' : '🐕' }}
                                    </div>
                                @endif
                                @if($name)
                                    <p class="text-white font-black text-center tracking-widest uppercase"
                                       style="font-size:11px; letter-spacing:2px;">{{ $name }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 裏面 --}}
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-400 mb-3">裏 面</p>

                    @if($isWood)
                        <div class="mx-auto mb-1 w-7 h-7 rounded-full border-4 border-gray-400"
                             style="box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);"></div>
                        <div class="relative mx-auto shadow-2xl flex flex-col items-center justify-center gap-1"
                             style="width:100px; height:148px; border-radius:6px;
                                    background-image:
                                        repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(150,100,50,0.08) 3px, rgba(150,100,50,0.08) 4px),
                                        linear-gradient(160deg, #e8c9a0 0%, #d4a96a 40%, #c49a5a 70%, #b8874a 100%);
                                    padding: 12px 8px;">
                            @if($name)
                                <p class="font-black text-center" style="font-size:9px; color:rgba(60,30,5,0.9);">{{ $name }}</p>
                                <div class="w-8 border-t border-amber-700 opacity-40 my-0.5"></div>
                            @endif
                            @if($breed)
                                <p class="text-center" style="font-size:7px; color:rgba(80,40,10,0.75);">{{ $breed }}</p>
                            @endif
                            @if($birthday)
                                <p class="text-center" style="font-size:7px; color:rgba(80,40,10,0.65);">{{ $birthday }}</p>
                            @endif
                            @if($message)
                                <p class="text-center italic" style="font-size:6px; color:rgba(80,40,10,0.5); margin-top:3px;">{{ $message }}</p>
                            @endif
                        </div>

                    @else
                        <div class="relative mx-auto bg-gray-900 shadow-2xl flex flex-col items-center justify-center"
                             style="width:120px; height:168px; border-radius:12px 12px 24px 24px;">
                            <div class="absolute bg-white rounded-full border-2 border-gray-700"
                                 style="width:10px; height:10px; top:8px; left:50%; transform:translateX(-50%);"></div>
                            <div class="flex flex-col items-center justify-center w-full h-full px-3 pt-5 pb-3 gap-1">
                                @if($name)
                                    <p class="text-white font-black text-center" style="font-size:10px;">{{ $name }}</p>
                                    <div class="w-8 border-t border-gray-600 my-0.5"></div>
                                @endif
                                @if($breed)
                                    <p class="text-gray-300 text-center" style="font-size:8px;">{{ $breed }}</p>
                                @endif
                                @if($birthday)
                                    <p class="text-gray-400 text-center" style="font-size:8px;">{{ $birthday }}</p>
                                @endif
                                @if($message)
                                    <p class="text-gray-500 text-center italic" style="font-size:7px; margin-top:4px;">{{ $message }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 mt-4">※ 実際の刻印はレーザー加工により仕上がります。</p>
        </div>

    {{-- ======== 基本商品 ======== --}}
    @else
        <div class="bg-slate-50 rounded-2xl p-6 mb-10 text-sm">
            <p class="text-slate-600">数量: <span class="font-bold text-slate-900 text-lg">{{ $data['quantity'] ?? 1 }}個</span></p>
        </div>
    @endif

    {{-- 入力内容サマリー --}}
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-6 mb-8">
        <h3 class="font-bold text-slate-700 text-sm mb-4">入力内容の確認</h3>
        <dl class="space-y-2 text-sm">
            @php
                $labels = [
                    'material'       => '素材',
                    'engraving_type' => '刻印タイプ',
                    'name'           => '名前',
                    'breed'          => '犬種',
                    'birthday'       => '誕生日',
                    'message'        => 'メッセージ',
                    'quantity'       => '数量',
                ];
                $skip = ['temp_image'];
                $displayValues = [
                    'material'       => ['black' => '黒メタル（軍番タグ）', 'wood' => '木製（ウッドキーホルダー）'],
                    'engraving_type' => ['nose_print' => '鼻紋', 'silhouette' => 'シルエット'],
                ];
            @endphp
            @foreach($data as $key => $val)
                @if(!in_array($key, $skip) && is_string($val) && $val !== '')
                    <div class="flex gap-3">
                        <dt class="text-slate-400 w-36 flex-shrink-0">{{ $labels[$key] ?? $key }}</dt>
                        <dd class="text-slate-900 font-medium">
                            {{ $displayValues[$key][$val] ?? $val }}
                        </dd>
                    </div>
                @endif
            @endforeach
        </dl>
    </div>

    {{-- アクションボタン --}}
    <form method="POST" action="{{ route('goods.order.store', $item) }}">
        @csrf
        @if($item->product_type->value === 'name_tag')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button type="submit" name="action" value="order"
                    class="bg-orange-500 text-white py-5 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100">
                    ✅ 購入を確定する
                </button>
                <button type="submit" name="action" value="consult"
                    class="bg-slate-100 text-slate-700 py-5 rounded-2xl font-bold text-lg hover:bg-slate-200 transition">
                    📧 メールで相談する
                </button>
            </div>
            <p class="text-xs text-slate-400 text-center mt-3">「メールで相談する」を選ぶと担当者がご要望をうかがいます（無料）</p>
        @else
            <button type="submit" name="action" value="order"
                class="w-full bg-orange-500 text-white py-5 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100">
                ✅ 購入を確定する
            </button>
        @endif
    </form>

    <a href="{{ route('goods.order.create', $item) }}"
       class="mt-4 w-full block text-center bg-slate-100 text-slate-700 py-5 rounded-2xl font-bold text-lg hover:bg-slate-200 transition">
        ← 修正する
    </a>
</div>
@endsection
