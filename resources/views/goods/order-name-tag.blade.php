@extends('layouts.app')
@section('title', 'ネームタグ 注文 | GUNIL PET SHOP')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl" x-data="nameTagForm()">

    <div class="mb-8">
        <a href="/goods" class="text-slate-400 text-sm hover:text-orange-500 transition">← グッズ一覧に戻る</a>
    </div>

    {{-- 商品画像スライダー --}}
    @php
        $allImages = collect();
        if ($item->thumbnail_image) $allImages->push(asset('storage/' . $item->thumbnail_image));
        foreach (($item->product_images ?? []) as $p) {
            $url = asset('storage/' . $p);
            if (! $allImages->contains($url)) $allImages->push($url);
        }
    @endphp

    <div x-data="{ current: 0, images: {{ $allImages->values()->toJson() }} }" class="mb-8">
        {{-- メイン画像 --}}
        <div class="relative rounded-[32px] overflow-hidden bg-slate-900 shadow-xl"
             style="aspect-ratio: 4/3;">
            <template x-if="images.length > 0">
                <template x-for="(img, i) in images" :key="i">
                    <img :src="img"
                         x-show="current === i"
                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300">
                </template>
            </template>
            <template x-if="images.length === 0">
                <div class="absolute inset-0 flex items-center justify-center text-6xl">🏷️</div>
            </template>

            {{-- 商品名オーバーレイ --}}
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-6 py-5">
                <p class="text-xs text-orange-400 font-bold uppercase tracking-widest mb-1">Name Tag</p>
                <h2 class="text-xl font-black text-white">{{ $item->name }}</h2>
                <p class="text-orange-400 font-black">¥{{ number_format($item->price) }}</p>
            </div>

            {{-- 前後ボタン --}}
            <template x-if="images.length > 1">
                <div>
                    <button type="button"
                            @click="current = (current - 1 + images.length) % images.length"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/40 hover:bg-black/70 text-white rounded-full font-bold text-xl transition">‹</button>
                    <button type="button"
                            @click="current = (current + 1) % images.length"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/40 hover:bg-black/70 text-white rounded-full font-bold text-xl transition">›</button>
                </div>
            </template>

            {{-- ドットインジケーター --}}
            <template x-if="images.length > 1">
                <div class="absolute top-3 right-3 flex gap-1.5">
                    <template x-for="(img, i) in images" :key="i">
                        <button type="button" @click="current = i"
                                :class="current === i ? 'bg-orange-400 w-4' : 'bg-white/50 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </template>
        </div>

        {{-- サムネイル列 --}}
        <template x-if="images.length > 1">
            <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                <template x-for="(img, i) in images" :key="i">
                    <button type="button" @click="current = i"
                            :class="current === i ? 'ring-2 ring-orange-500 opacity-100' : 'ring-1 ring-slate-200 opacity-50'"
                            class="flex-shrink-0 w-14 h-14 rounded-xl overflow-hidden transition-all">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </template>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('goods.order.preview', $item) }}" enctype="multipart/form-data"
              @submit="prepareSubmit">
            @csrf

            {{-- hidden: カメラ撮影データ --}}
            <input type="hidden" name="captured_image" x-model="capturedBase64">

            {{-- STEP 1: 素材 --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">STEP 1 — 素材を選ぶ</p>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="material" value="black" class="sr-only peer"
                               x-model="material" {{ old('material', 'black') === 'black' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-slate-900 peer-checked:bg-slate-900 rounded-2xl p-5 text-center transition">
                            <div class="mx-auto mb-3 flex justify-center">
                                <div class="relative bg-gray-900 border-2 border-gray-700"
                                     style="width:40px; height:56px; border-radius:5px 5px 9px 9px;">
                                    <div class="absolute bg-gray-600 rounded-full"
                                         style="width:5px; height:5px; top:4px; left:50%; transform:translateX(-50%);"></div>
                                </div>
                            </div>
                            <div class="font-black text-sm transition" :class="material === 'black' ? 'text-white' : 'text-slate-900'">黒メタル</div>
                            <div class="text-xs text-slate-400 mt-1">軍番タグ</div>
                        </div>
                    </label>
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
                    <label class="cursor-pointer">
                        <input type="radio" name="engraving_type" value="nose_print" class="sr-only peer"
                               x-model="engravingType" {{ old('engraving_type', 'nose_print') === 'nose_print' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 rounded-2xl p-5 text-center transition">
                            <div class="text-3xl mb-2">🐽</div>
                            <div class="font-black text-sm text-slate-900">鼻紋</div>
                            <div class="text-xs text-slate-400 mt-1">お鼻の写真を刻印</div>
                        </div>
                    </label>
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

            {{-- STEP 3: 写真撮影 / アップロード --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">STEP 3 — 写真を撮影 / アップロード</p>

                {{-- 撮影済みプレビュー --}}
                <template x-if="capturedBase64 || previewUrl">
                    <div class="mb-4 text-center">
                        <div class="relative inline-block">
                            <img :src="capturedBase64 || previewUrl"
                                 class="max-h-56 mx-auto rounded-2xl object-cover shadow-lg border-4 border-orange-300">
                            <button type="button" @click="resetPhoto"
                                    class="absolute top-2 right-2 bg-white text-slate-600 rounded-full w-8 h-8 text-sm font-bold shadow hover:bg-red-50 hover:text-red-500 transition">✕</button>
                        </div>
                        <p class="text-xs text-green-600 font-bold mt-2">✅ 写真が選択されています</p>
                    </div>
                </template>

                {{-- ボタン群 --}}
                <template x-if="!capturedBase64 && !previewUrl">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        {{-- カメラ撮影ボタン --}}
                        <button type="button" @click="openCamera"
                                class="flex flex-col items-center justify-center gap-2 border-2 border-slate-200 rounded-2xl p-5 hover:border-orange-400 hover:bg-orange-50 transition">
                            <span class="text-3xl">📸</span>
                            <span class="font-bold text-sm text-slate-700">カメラで撮影</span>
                            <span class="text-xs text-slate-400">ガイド枠に合わせて撮影</span>
                        </button>
                        {{-- ファイル選択 --}}
                        <label class="flex flex-col items-center justify-center gap-2 border-2 border-slate-200 rounded-2xl p-5 hover:border-orange-400 hover:bg-orange-50 transition cursor-pointer">
                            <span class="text-3xl">🖼️</span>
                            <span class="font-bold text-sm text-slate-700">写真を選択</span>
                            <span class="text-xs text-slate-400">アルバムから選ぶ</span>
                            <input type="file" name="uploaded_image" accept="image/*"
                                   class="hidden" @change="onFileChange($event)">
                        </label>
                    </div>
                </template>

                @error('uploaded_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                @error('captured_image')  <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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

{{-- ===== カメラモーダル ===== --}}
<div x-show="cameraOpen" x-cloak
     class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-black"
     style="display:none;">

    {{-- ヘッダー --}}
    <div class="absolute top-0 left-0 right-0 flex items-center justify-between px-4 py-3 z-10">
        <button type="button" @click="closeCamera"
                class="text-white text-sm font-bold bg-white/20 rounded-full px-4 py-2">✕ キャンセル</button>
        <p class="text-white text-xs font-bold opacity-60"
           x-text="engravingType === 'nose_print' ? '🐽 鼻を枠の中に合わせてください' : '🐕 全身を枠の中に入れてください'"></p>
        <div class="w-24"></div>
    </div>

    {{-- カメラ映像 --}}
    <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
        <video x-ref="video" autoplay playsinline
               class="absolute inset-0 w-full h-full object-cover"></video>

        {{-- ガイドオーバーレイ (SVG) --}}
        <svg class="absolute inset-0 w-full h-full pointer-events-none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <mask id="guide-mask">
                    <rect width="100%" height="100%" fill="white"/>
                    {{-- 鼻紋：楕円 --}}
                    <template x-if="engravingType === 'nose_print'">
                        <ellipse cx="50%" cy="50%" rx="35%" ry="28%" fill="black"/>
                    </template>
                    {{-- シルエット：長方形 --}}
                    <template x-if="engravingType === 'silhouette'">
                        <rect x="10%" y="15%" width="80%" height="70%" rx="16" fill="black"/>
                    </template>
                </mask>
            </defs>
            {{-- 枠外を半透明黒でマスク --}}
            <rect width="100%" height="100%" fill="rgba(0,0,0,0.55)" mask="url(#guide-mask)"/>
            {{-- ガイド枠線 --}}
            <template x-if="engravingType === 'nose_print'">
                <ellipse cx="50%" cy="50%" rx="35%" ry="28%"
                         fill="none" stroke="rgba(255,165,0,0.9)" stroke-width="2.5"
                         stroke-dasharray="8 4"/>
            </template>
            <template x-if="engravingType === 'silhouette'">
                <rect x="10%" y="15%" width="80%" height="70%" rx="16"
                      fill="none" stroke="rgba(255,165,0,0.9)" stroke-width="2.5"
                      stroke-dasharray="8 4"/>
            </template>
        </svg>

        {{-- 隠しcanvas --}}
        <canvas x-ref="canvas" class="hidden"></canvas>
    </div>

    {{-- シャッターボタン --}}
    <div class="absolute bottom-10 left-0 right-0 flex justify-center">
        <button type="button" @click="capture"
                class="w-20 h-20 rounded-full bg-white border-4 border-orange-400 shadow-2xl active:scale-95 transition flex items-center justify-center">
            <div class="w-14 h-14 rounded-full bg-orange-400"></div>
        </button>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

<script>
function nameTagForm() {
    return {
        material:       '{{ old('material', 'black') }}',
        engravingType:  '{{ old('engraving_type', 'nose_print') }}',
        previewUrl:     null,
        capturedBase64: '',
        cameraOpen:     false,
        stream:         null,

        openCamera() {
            this.cameraOpen = true;
            this.$nextTick(() => {
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 960 } }
                }).then(s => {
                    this.stream = s;
                    this.$refs.video.srcObject = s;
                }).catch(() => {
                    alert('カメラへのアクセスが許可されていません。\n設定でカメラを許可するか、写真を選択してください。');
                    this.cameraOpen = false;
                });
            });
        },

        closeCamera() {
            if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null; }
            this.cameraOpen = false;
        },

        capture() {
            const video  = this.$refs.video;
            const canvas = this.$refs.canvas;
            const vw = video.videoWidth;
            const vh = video.videoHeight;
            canvas.width  = vw;
            canvas.height = vh;
            const ctx = canvas.getContext('2d');

            // ガイド枠のマスクをcanvasに描画（サーバー側と座標を合わせる）
            ctx.drawImage(video, 0, 0, vw, vh);

            // 枠外を黒塗り
            ctx.save();
            ctx.fillStyle = 'black';
            if (this.engravingType === 'nose_print') {
                // 楕円マスク: rx=35%, ry=28% of image size
                const rx = vw * 0.35, ry = vh * 0.28;
                const cx = vw / 2,    cy = vh / 2;
                ctx.beginPath();
                ctx.rect(0, 0, vw, vh);
                ctx.ellipse(cx, cy, rx, ry, 0, 0, Math.PI * 2);
                ctx.evenOdd = true;
                ctx.fill('evenodd');
            } else {
                // 長方形マスク: x=10%, y=15%, w=80%, h=70%
                const mx = vw * 0.10, my = vh * 0.15, mw = vw * 0.80, mh = vh * 0.70;
                ctx.beginPath();
                ctx.rect(0, 0, vw, vh);
                ctx.rect(mx, my, mw, mh);
                ctx.fill('evenodd');
            }
            ctx.restore();

            this.capturedBase64 = canvas.toDataURL('image/jpeg', 0.92);
            this.closeCamera();
        },

        onFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => { this.previewUrl = ev.target.result; };
            reader.readAsDataURL(file);
        },

        resetPhoto() {
            this.capturedBase64 = '';
            this.previewUrl = null;
        },

        prepareSubmit(e) {
            // 写真が何もない場合はブロック
            const hasFile    = document.querySelector('input[name=uploaded_image]')?.files?.length > 0;
            const hasCapture = this.capturedBase64 !== '';
            if (!hasFile && !hasCapture) {
                e.preventDefault();
                alert('写真を撮影するか、アルバムから選択してください。');
            }
        }
    };
}
</script>
@endsection
