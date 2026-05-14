@extends('layouts.app')
@section('title', 'ネームタグ 注文 | GUNIL PET SHOP')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl" x-data="nameTagForm()">

    <div class="mb-8">
        <a href="/goods" class="text-slate-400 text-sm hover:text-orange-500 transition">← グッズ一覧に戻る</a>
    </div>

    {{-- 商品情報 --}}
    <div class="bg-slate-900 rounded-[32px] p-6 mb-8 flex gap-5 items-center">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-20 h-20 rounded-2xl object-cover">
        @else
            <div class="w-20 h-20 rounded-2xl bg-slate-700 flex items-center justify-center text-3xl">🏷️</div>
        @endif
        <div>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Name Tag</p>
            <h2 class="text-xl font-black text-white">{{ $item->name }}</h2>
            <p class="text-orange-400 font-black text-lg">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('goods.order.preview', $item) }}" enctype="multipart/form-data">
            @csrf

            {{-- STEP 1: 素材 --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">STEP 1 — 素材を選ぶ</p>
                <div class="grid grid-cols-2 gap-4">

                    {{-- 黒メタル --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="material" value="black" class="sr-only peer"
                               x-model="material" {{ old('material', 'black') === 'black' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-slate-900 peer-checked:bg-slate-900 rounded-2xl p-5 text-center transition group">
                            {{-- 黒タグの形状プレビュー --}}
                            <div class="mx-auto mb-3 flex justify-center">
                                <div class="relative bg-gray-900 border-2 border-gray-700 peer-checked:border-gray-500"
                                     style="width:40px; height:56px; border-radius:5px 5px 9px 9px;">
                                    <div class="absolute bg-gray-600 rounded-full"
                                         style="width:5px; height:5px; top:4px; left:50%; transform:translateX(-50%);"></div>
                                </div>
                            </div>
                            <div class="font-black text-sm peer-checked:text-white group-[.peer-checked]:text-white transition"
                                 :class="material === 'black' ? 'text-white' : 'text-slate-900'">黒メタル</div>
                            <div class="text-xs mt-1 transition"
                                 :class="material === 'black' ? 'text-slate-400' : 'text-slate-400'">軍番タグ</div>
                        </div>
                    </label>

                    {{-- 木製 --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="material" value="wood" class="sr-only peer"
                               x-model="material" {{ old('material') === 'wood' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-amber-700 peer-checked:bg-amber-50 rounded-2xl p-5 text-center transition">
                            <div class="mx-auto mb-3 flex justify-center">
                                <div class="relative shadow"
                                     style="width:38px; height:56px; border-radius:4px;
                                            background: linear-gradient(160deg, #e8c9a0, #c49a5a);
                                            background-image: repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(150,100,50,0.1) 3px, rgba(150,100,50,0.1) 4px),
                                                              linear-gradient(160deg, #e8c9a0, #c49a5a);">
                                    <div class="absolute rounded-full border border-amber-800 bg-amber-100"
                                         style="width:6px; height:6px; top:3px; left:50%; transform:translateX(-50%);"></div>
                                </div>
                            </div>
                            <div class="font-black text-sm" :class="material === 'wood' ? 'text-amber-900' : 'text-slate-900'">木製</div>
                            <div class="text-xs text-slate-400 mt-1">ウッドキーホルダー</div>
                        </div>
                    </label>
                </div>
                @error('material') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- STEP 2: 刻印タイプ --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">STEP 2 — 刻印タイプを選ぶ</p>
                <div class="grid grid-cols-2 gap-4">

                    {{-- 鼻紋 --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="engraving_type" value="nose_print" class="sr-only peer"
                               x-model="engravingType" {{ old('engraving_type', 'nose_print') === 'nose_print' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 rounded-2xl p-5 text-center transition">
                            <div class="text-3xl mb-2">🐽</div>
                            <div class="font-black text-sm text-slate-900">鼻紋</div>
                            <div class="text-xs text-slate-400 mt-1">お鼻の写真を刻印</div>
                        </div>
                    </label>

                    {{-- シルエット --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="engraving_type" value="silhouette" class="sr-only peer"
                               x-model="engravingType" {{ old('engraving_type') === 'silhouette' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 rounded-2xl p-5 text-center transition">
                            <div class="text-3xl mb-2">🐕</div>
                            <div class="font-black text-sm text-slate-900">シルエット</div>
                            <div class="text-xs text-slate-400 mt-1">全身写真をシルエットに</div>
                        </div>
                    </label>
                </div>
                @error('engraving_type') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- 撮影ガイド（動的） --}}
            <div class="rounded-2xl p-4 mb-8 text-sm border"
                 :class="engravingType === 'nose_print'
                     ? 'bg-blue-50 border-blue-100 text-blue-700'
                     : 'bg-green-50 border-green-100 text-green-700'">
                <template x-if="engravingType === 'nose_print'">
                    <div>
                        <p class="font-bold mb-1">📷 鼻紋撮影のコツ</p>
                        @if($item->nose_print_guide)
                            <p>{{ $item->nose_print_guide }}</p>
                        @else
                            <p>鼻の正面からピントを合わせて撮影してください。フラッシュなしで明るい場所での撮影を推奨します。</p>
                        @endif
                    </div>
                </template>
                <template x-if="engravingType === 'silhouette'">
                    <div>
                        <p class="font-bold mb-1">📷 シルエット撮影のコツ</p>
                        @if($item->silhouette_guide)
                            <p>{{ $item->silhouette_guide }}</p>
                        @else
                            <p>横向きで全身が写るように撮影してください。背景はシンプルな方が仕上がりがきれいになります。</p>
                        @endif
                    </div>
                </template>
            </div>

            {{-- STEP 3: 写真アップロード --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">STEP 3 — 写真をアップロード</p>
                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-orange-300 transition"
                     :class="previewUrl ? 'border-orange-300 bg-orange-50' : ''">
                    <template x-if="!previewUrl">
                        <div>
                            <div class="text-4xl mb-3" x-text="engravingType === 'nose_print' ? '🐽' : '🐕'"></div>
                            <p class="text-sm text-slate-500 mb-3" x-text="engravingType === 'nose_print' ? '鼻紋の写真を選択' : 'シルエット用の写真を選択'"></p>
                        </div>
                    </template>
                    <template x-if="previewUrl">
                        <div class="mb-3">
                            <img :src="previewUrl" class="max-h-40 mx-auto rounded-xl object-cover shadow">
                        </div>
                    </template>
                    <input type="file" name="uploaded_image" accept="image/*" required
                           @change="onFileChange($event)"
                           class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                  file:font-bold file:bg-orange-100 file:text-orange-600">
                    <p class="text-xs text-slate-400 mt-2">JPG・PNG・WEBP / 最大10MB</p>
                </div>
                @error('uploaded_image') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- STEP 4: 名前・情報 --}}
            <div class="mb-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">STEP 4 — 名前・情報を入力</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">名前（表面・裏面に入ります） <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="50"
                               placeholder="例：MOCHI"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 uppercase">
                        <p class="text-xs text-slate-400 mt-1">アルファベット推奨（大文字で刻印）</p>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">犬種</label>
                        <input type="text" name="breed" value="{{ old('breed') }}" maxlength="50"
                               placeholder="例：ブルドッグ"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">誕生日</label>
                        <input type="text" name="birthday" value="{{ old('birthday') }}" maxlength="20"
                               placeholder="例：2020.05.03"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">裏面メッセージ（100文字以内）</label>
                        <textarea name="message" maxlength="100" rows="2"
                                  placeholder="例：大好きだよ"
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">{{ old('message') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-500 transition shadow-lg">
                確認画面へ進む →
            </button>
        </form>
    </div>
</div>

<script>
function nameTagForm() {
    return {
        material: '{{ old('material', 'black') }}',
        engravingType: '{{ old('engraving_type', 'nose_print') }}',
        previewUrl: null,
        onFileChange(e) {
            const file = e.target.files[0];
            if (!file) { this.previewUrl = null; return; }
            const reader = new FileReader();
            reader.onload = (ev) => { this.previewUrl = ev.target.result; };
            reader.readAsDataURL(file);
        }
    };
}
</script>
@endsection
