@extends('layouts.app')

@section('title', '犬グッズ | INU GOODS')

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
                    <div class="group">
                        <div class="relative bg-white rounded-[40px] aspect-square mb-6 overflow-hidden border border-slate-100 shadow-sm transition-all group-hover:shadow-2xl group-hover:-translate-y-2">
                            @if($item->thumbnail_image)
                                <img src="{{ asset('storage/' . $item->thumbnail_image) }}"
                                     alt="{{ $item->name }}"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl group-hover:bg-orange-50 transition-colors">🐶</div>
                            @endif
                        </div>
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
