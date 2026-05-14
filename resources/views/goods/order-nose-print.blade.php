@extends('layouts.app')
@section('title', '鼻紋ネームタグ 注文 | GUNIL PET SHOP')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl">
    <div class="mb-8">
        <a href="/goods" class="text-slate-400 text-sm hover:text-orange-500 transition">← グッズ一覧に戻る</a>
    </div>

    {{-- 商品情報 --}}
    <div class="bg-orange-50 rounded-[32px] p-6 mb-8 flex gap-5 items-center border border-orange-100">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-20 h-20 rounded-2xl object-cover">
        @else
            <div class="w-20 h-20 rounded-2xl bg-orange-100 flex items-center justify-center text-3xl">🐽</div>
        @endif
        <div>
            <p class="text-xs text-orange-500 font-bold uppercase">Nose Print Name Tag</p>
            <h2 class="text-xl font-black text-slate-900">{{ $item->name }}</h2>
            <p class="text-orange-500 font-black text-lg">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    @if($item->nose_print_guide)
        <div class="bg-blue-50 rounded-2xl p-5 mb-8 text-sm text-blue-700 border border-blue-100">
            📷 <strong>撮影ガイド</strong><br>
            <span class="mt-1 block">{{ $item->nose_print_guide }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
        <form method="POST" action="{{ route('goods.order.preview', $item) }}" enctype="multipart/form-data">
            @csrf

            {{-- タグ形状 --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">タグの形を選択 <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="tag_shape" value="straight" class="sr-only peer" {{ old('tag_shape', 'straight') === 'straight' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 rounded-2xl p-6 text-center transition">
                            <div class="w-16 h-8 bg-slate-200 peer-checked:bg-orange-200 rounded-lg mx-auto mb-3"></div>
                            <div class="font-bold text-slate-900 text-sm">一字型</div>
                            <div class="text-xs text-slate-400 mt-1">横長のスタンダード</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tag_shape" value="round" class="sr-only peer" {{ old('tag_shape') === 'round' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 rounded-2xl p-6 text-center transition">
                            <div class="w-12 h-12 bg-slate-200 rounded-full mx-auto mb-3"></div>
                            <div class="font-bold text-slate-900 text-sm">ラウンド型</div>
                            <div class="text-xs text-slate-400 mt-1">丸型のかわいいデザイン</div>
                        </div>
                    </label>
                </div>
                @error('tag_shape') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- 鼻紋写真 --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">鼻紋写真をアップロード <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-orange-300 transition">
                    <div class="text-3xl mb-2">🐽</div>
                    <input type="file" name="uploaded_image" accept="image/*" required
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-bold file:bg-orange-100 file:text-orange-600">
                    <p class="text-xs text-slate-400 mt-2">鼻の正面から明るい場所で撮影 / JPG・PNG / 最大10MB</p>
                </div>
                @error('uploaded_image') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- 裏面情報 --}}
            <div class="mb-8">
                <h4 class="text-sm font-bold text-slate-700 mb-4">裏面に入れる情報</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">名前 <span class="text-red-500">*</span></label>
                        <input type="text" name="back_name" value="{{ old('back_name') }}" required maxlength="50"
                               placeholder="例：ポチ"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('back_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">犬種</label>
                        <input type="text" name="back_breed" value="{{ old('back_breed') }}" maxlength="50"
                               placeholder="例：トイプードル"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">誕生日</label>
                        <input type="text" name="back_birthday" value="{{ old('back_birthday') }}" maxlength="20"
                               placeholder="例：2020.05.03"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">メッセージ（100文字以内）</label>
                        <textarea name="back_message" maxlength="100" rows="2"
                                  placeholder="例：大好きだよ、ポチ"
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">{{ old('back_message') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-500 transition shadow-lg">
                確認画面へ進む →
            </button>
        </form>
    </div>
</div>
@endsection
