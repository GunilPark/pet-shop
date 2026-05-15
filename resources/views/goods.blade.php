@extends('layouts.app')

@section('title', '犬グッズ | GUNIL PET SHOP')

@section('content')
    <div class="bg-slate-50 border-b border-slate-100 py-20">
        <div class="container mx-auto px-6 text-center">
            <span class="text-orange-600 font-bold tracking-widest uppercase text-sm mb-4 block">Selected Items</span>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">厳選犬グッズ</h1>
            <p class="text-slate-500 max-w-2xl mx-auto leading-relaxed italic">
                愛犬との暮らしをより豊かに、より快適に。<br>
                スタッフが実際に使用して「本当にお勧めできる」と感じた高品質なアイテムだけを集めました。
            </p>
        </div>
    </div>

    <div class="container mx-auto px-6 py-20">

        @if($items->isEmpty())
            <div class="text-center py-24 text-slate-400">
                <div class="text-5xl mb-6">🐾</div>
                <p class="font-bold text-xl">現在、商品は準備中です。</p>
                <p class="text-sm mt-2">もうしばらくお待ちください。</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach($items as $item)
                    @php
                        $images = collect();
                        if ($item->thumbnail_image) $images->push(asset('storage/' . $item->thumbnail_image));
                        foreach (($item->product_images ?? []) as $p) {
                            $url = asset('storage/' . $p);
                            if (! $images->contains($url)) $images->push($url);
                        }
                    @endphp

                    <div class="group" x-data="{ current: 0, images: {{ $images->values()->toJson() }} }">

                        {{-- メイン画像 --}}
                        <div class="relative bg-white rounded-[40px] aspect-square mb-3 overflow-hidden border border-slate-100 shadow-sm transition-all group-hover:shadow-2xl group-hover:-translate-y-2 cursor-pointer"
                             @click="$dispatch('open-gallery', { images: images, start: current })">
                            <template x-if="images.length > 0">
                                <img :src="images[current]" alt="{{ $item->name }}"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </template>
                            <template x-if="images.length === 0">
                                <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl group-hover:bg-orange-50 transition-colors">🐶</div>
                            </template>

                            {{-- 枚数バッジ --}}
                            <template x-if="images.length > 1">
                                <div class="absolute top-3 right-3 bg-black/50 text-white text-xs font-bold px-2 py-1 rounded-full"
                                     x-text="(current + 1) + ' / ' + images.length"></div>
                            </template>

                            {{-- 前後ボタン --}}
                            <template x-if="images.length > 1">
                                <div>
                                    <button type="button"
                                            @click.stop="current = (current - 1 + images.length) % images.length"
                                            class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 rounded-full text-slate-700 font-bold shadow hover:bg-white transition opacity-0 group-hover:opacity-100 text-lg">‹</button>
                                    <button type="button"
                                            @click.stop="current = (current + 1) % images.length"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 rounded-full text-slate-700 font-bold shadow hover:bg-white transition opacity-0 group-hover:opacity-100 text-lg">›</button>
                                </div>
                            </template>
                        </div>

                        {{-- サムネイル列 --}}
                        <template x-if="images.length > 1">
                            <div class="flex gap-2 mb-4 px-1 overflow-x-auto pb-1">
                                <template x-for="(img, i) in images" :key="i">
                                    <button type="button" @click="current = i"
                                            :class="current === i ? 'ring-2 ring-orange-500' : 'ring-1 ring-slate-200 opacity-60'"
                                            class="flex-shrink-0 w-12 h-12 rounded-xl overflow-hidden transition">
                                        <img :src="img" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </template>

                        <div class="px-4">
                            <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-500 transition-colors">{{ $item->name }}</h3>
                            @if($item->description)
                                <p class="text-slate-400 text-sm mb-4 italic line-clamp-2">{{ $item->description }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-black text-slate-900">
                                    ¥{{ number_format($item->price) }}
                                    <span class="text-sm font-medium text-slate-400">(税込)</span>
                                </span>
                                @auth
                                    <a href="{{ route('goods.order.create', $item) }}"
                                       class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-orange-500 transition-colors shadow-lg">＋</a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-orange-500 transition-colors shadow-lg">＋</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ライトボックス --}}
            <div x-data="goodsGallery()" x-cloak
                 @open-gallery.window="open($event.detail.images, $event.detail.start)"
                 @keydown.arrow-right.window="next()"
                 @keydown.arrow-left.window="prev()"
                 @keydown.escape.window="close()">
                <template x-if="show">
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
                         @click.self="close()">
                        <button @click="close()"
                                class="absolute top-4 right-4 text-white/70 hover:text-white text-3xl font-light leading-none">✕</button>
                        <button @click="prev()"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-5xl font-light leading-none">‹</button>
                        <img :src="images[current]"
                             class="max-h-[85vh] max-w-[90vw] object-contain rounded-2xl shadow-2xl">
                        <button @click="next()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-5xl font-light leading-none">›</button>
                        <p class="absolute bottom-4 text-white/50 text-sm font-bold"
                           x-text="(current + 1) + ' / ' + images.length"></p>
                    </div>
                </template>
            </div>
            <style>[x-cloak]{display:none!important}</style>
            <script>
            function goodsGallery() {
                return {
                    show: false, images: [], current: 0,
                    open(imgs, start) { this.images = imgs; this.current = start ?? 0; this.show = true; },
                    close() { this.show = false; },
                    next() { if (this.show) this.current = (this.current + 1) % this.images.length; },
                    prev() { if (this.show) this.current = (this.current - 1 + this.images.length) % this.images.length; },
                };
            }
            </script>
        @endif
    </div>

    <div class="container mx-auto px-6 py-10">
        <div class="bg-orange-500 rounded-[48px] p-12 text-white flex flex-col md:flex-row items-center justify-between shadow-2xl shadow-orange-200 overflow-hidden relative">
            <div class="relative z-10">
                <h2 class="text-3xl font-black mb-4 italic">Special Gift for You!</h2>
                <p class="opacity-90 font-medium">新規会員登録で、初回購入10%OFFクーポンプレゼント。</p>
            </div>
            <button class="relative z-10 mt-8 md:mt-0 bg-white text-orange-600 px-10 py-4 rounded-2xl font-bold hover:bg-slate-900 hover:text-white transition-all shadow-xl">登録してクーポンGET</button>
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
        </div>
    </div>
@endsection
