@extends('layouts.app')

@section('title', '犬RUNイベント | GUNIL PET SHOP')

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
            
            <div class="flex flex-col md:flex-row bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden mb-12 hover:shadow-xl transition-all group">
                <div class="md:w-1/4 bg-slate-900 text-white flex flex-col items-center justify-center py-10 group-hover:bg-orange-500 transition-colors">
                    <span class="text-sm font-bold opacity-70 mb-1">2026.03</span>
                    <span class="text-5xl font-black mb-1">15</span>
                    <span class="text-sm font-bold bg-white/20 px-3 py-1 rounded-full text-white uppercase">Sun</span>
                </div>
                <div class="md:w-3/4 p-10 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-orange-100 text-orange-600 text-xs font-bold px-3 py-1 rounded-full">予約受付中</span>
                            <span class="text-slate-400 text-sm flex items-center gap-1">📍 代々木公園 特設会場</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-orange-500 transition-colors">春のワンちゃん大運動会 2026</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6 italic">
                            毎年大人気の運動会！かけっこや障害物競走など、初心者でも楽しめる種目が盛りだくさんです。参加者全員にオリジナルおやつをプレゼント！
                        </p>
                    </div>
                    <div class="flex items-center justify-between mt-4 border-t border-slate-50 pt-6">
                        <div class="text-sm">
                            <span class="text-slate-400">定員:</span> <span class="font-bold text-slate-800">30組</span> 
                            <span class="mx-2 text-slate-200">|</span>
                            <span class="text-slate-400">参加費:</span> <span class="font-bold text-orange-500">¥2,500</span>
                        </div>
                        <button class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-orange-500 transition-colors shadow-lg shadow-slate-200">予約ページへ進む</button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden mb-12 opacity-80 group">
                <div class="md:w-1/4 bg-slate-200 text-slate-500 flex flex-col items-center justify-center py-10">
                    <span class="text-sm font-bold opacity-70 mb-1">2026.04</span>
                    <span class="text-5xl font-black mb-1">05</span>
                    <span class="text-sm font-bold border border-slate-300 px-3 py-1 rounded-full uppercase">Sun</span>
                </div>
                <div class="md:w-3/4 p-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full">準備中</span>
                        <span class="text-slate-400 text-sm flex items-center gap-1">📍 駒沢オリンピック公園</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-400 mb-4">パピー交流会 ＆ マナー講習</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6 italic">
                        生後12ヶ月以内のパピー限定！他のワンちゃんとの接し方や、外出時のマナーをプロのトレーナーが優しくレクチャーします。
                    </p>
                    <div class="text-sm text-slate-400 font-bold">※ 詳細は3月中旬に公開予定です。</div>
                </div>
            </div>

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