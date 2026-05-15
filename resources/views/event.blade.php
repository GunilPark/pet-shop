@extends('layouts.app')

@section('title', '犬RUNイベント | INU GOODS')

@section('content')
    <div class="bg-orange-50 border-b border-orange-100 py-20">
        <div class="container mx-auto px-6 text-center">
            <span class="text-orange-600 font-bold tracking-widest uppercase text-sm mb-4 block">Enjoy with Your Dog</span>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">犬RUNイベント</h1>
            <p class="text-slate-600 max-w-2xl mx-auto leading-relaxed italic">
                青空の下で、愛犬と思いっきり走り回りませんか？<br>
                プロのドッグトレーナーによる社会性講習から、楽しい運動会まで多彩なプログラムをご用意しています。
            </p>
        </div>
    </div>

    <div class="container mx-auto px-6 py-20">
        <div class="max-w-4xl mx-auto">

            @if($events->isEmpty())
                <div class="text-center py-24 text-slate-400">
                    <div class="text-5xl mb-6">📅</div>
                    <p class="font-bold text-xl">現在、開催予定のイベントはありません。</p>
                    <p class="text-sm mt-2">次回のイベントをお楽しみに！</p>
                </div>
            @else
                @foreach($events as $event)
                    <div class="flex flex-col md:flex-row bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden mb-12 hover:shadow-xl transition-all group">
                        <div class="md:w-1/4 bg-slate-900 text-white flex flex-col items-center justify-center py-10 group-hover:bg-orange-500 transition-colors">
                            <span class="text-sm font-bold opacity-70 mb-1">{{ $event->started_at->format('Y.m') }}</span>
                            <span class="text-5xl font-black mb-1">{{ $event->started_at->format('d') }}</span>
                            <span class="text-sm font-bold bg-white/20 px-3 py-1 rounded-full text-white uppercase">
                                {{ $event->started_at->isoFormat('ddd') }}
                            </span>
                        </div>
                        <div class="md:w-3/4 p-10 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="bg-orange-100 text-orange-600 text-xs font-bold px-3 py-1 rounded-full">予約受付中</span>
                                    @if($event->location)
                                        <span class="text-slate-400 text-sm flex items-center gap-1">
                                            📍 {{ $event->location }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-orange-500 transition-colors">
                                    {{ $event->title }}
                                </h3>
                                @if($event->description)
                                    <p class="text-slate-500 text-sm leading-relaxed mb-6 italic">
                                        {{ $event->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-4 border-t border-slate-50 pt-6">
                                <div class="text-sm">
                                    @if($event->max_capacity)
                                        <span class="text-slate-400">定員:</span>
                                        <span class="font-bold text-slate-800">{{ $event->max_capacity }}組</span>
                                        <span class="mx-2 text-slate-200">|</span>
                                    @endif
                                    <span class="text-slate-400">開催時間:</span>
                                    <span class="font-bold text-slate-800">
                                        {{ $event->started_at->format('H:i') }} 〜 {{ $event->ended_at->format('H:i') }}
                                    </span>
                                </div>
                                @auth
                                    <a href="{{ route('event.apply.create', $event) }}"
                                       class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-orange-500 transition-colors shadow-lg shadow-slate-200">
                                        参加申請する
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="bg-orange-500 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-orange-600 transition-colors shadow-lg">
                                        ログインして申請
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

    <div class="bg-white py-24 border-t border-slate-100">
        <div class="container mx-auto px-6 text-center">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-8 text-3xl">📧</div>
            <h2 class="text-2xl font-bold mb-4">イベントの最新情報を受け取る</h2>
            <p class="text-slate-500 mb-10 text-sm italic">公式LINEやメールマガジンで、次回の開催情報をいち早くお届けします。</p>
            <div class="flex justify-center gap-4">
                <button class="bg-[#06C755] text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-green-100">LINEで友だち追加</button>
            </div>
        </div>
    </div>
@endsection
