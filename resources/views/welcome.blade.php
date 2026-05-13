@extends('layouts.app')

@section('title', 'TOP | GUNIL PET SHOP')

@section('content')
    <div class="relative bg-white overflow-hidden border-b border-orange-50">
        <div class="container mx-auto px-6 py-16 md:py-24 flex flex-col md:flex-row items-center relative z-10">
            <div class="md:w-3/5 text-center md:text-left">
                <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-sm font-bold mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    New Experience for Pets
                </div>
                <h2 class="text-4xl md:text-6xl font-black mb-8 leading-[1.2] text-slate-900">
                    愛犬との毎日を、<br>
                    <span class="text-orange-500">もっと特別に。</span>
                </h2>
                <p class="text-slate-500 mb-12 text-lg md:text-xl leading-relaxed max-w-xl">
                    クニイル・ペットショップは、愛犬の健康を考えた厳選グッズと、<br class="hidden md:block">
                    心躍る「犬RUNイベント」をお届けする特別な場所です。
                </p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center md:justify-start">
                    <a href="/event" class="bg-orange-500 text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 shadow-2xl shadow-orange-200 transition-all hover:-translate-y-1 text-center">
                        イベントを見る
                    </a>
                    <a href="/goods" class="bg-white text-slate-800 border-2 border-slate-100 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all text-center">
                        グッズを探す
                    </a>
                </div>
            </div>
            
            <div class="md:w-2/5 mt-16 md:mt-0 relative">
                <div class="relative w-72 h-72 md:w-96 md:h-96 mx-auto">
                    <div class="absolute inset-0 bg-orange-200 rounded-[40px] rotate-6"></div>
                    <div class="absolute inset-0 bg-slate-100 rounded-[40px] -rotate-3 overflow-hidden border-4 border-white shadow-xl flex items-center justify-center">
                        <span class="text-slate-400 font-bold">🐶 PHOTO HERE</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[500px] h-[500px] bg-orange-50 rounded-full opacity-50"></div>
    </div>

    <div class="py-24 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-3xl font-bold text-slate-900 mb-4">Our Service</h3>
                <div class="h-1.5 w-12 bg-orange-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-[32px] shadow-sm hover:shadow-xl transition-all group border border-orange-50">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:rotate-12 transition-transform">🏃‍♂️</div>
                    <h4 class="text-xl font-bold mb-4">犬RUNイベント</h4>
                    <p class="text-slate-500 leading-relaxed text-sm">広いドッグランでお友達と一緒に。愛犬の運動不足解消と社会性を育むイベントを定期開催。</p>
                </div>
                
                <div class="bg-white p-10 rounded-[32px] shadow-sm hover:shadow-xl transition-all group border border-orange-50">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:rotate-12 transition-transform">🦴</div>
                    <h4 class="text-xl font-bold mb-4">厳選グッズ販売</h4>
                    <p class="text-slate-500 leading-relaxed text-sm">スタッフが実際に試して納得した、高品質で安全な無添加フードや知育玩具のみをセレクト。</p>
                </div>

                <div class="bg-white p-10 rounded-[32px] shadow-sm hover:shadow-xl transition-all group border border-orange-50">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-3xl mb-8 group-hover:rotate-12 transition-transform">📸</div>
                    <h4 class="text-xl font-bold mb-4">メモリアル撮影</h4>
                    <p class="text-slate-500 leading-relaxed text-sm">イベント中はプロカメラマンが同行。最高の一瞬を形に残すお手伝いをいたします。</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-24 text-center">
        <div class="bg-slate-900 rounded-[48px] p-12 md:p-20 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-2xl md:text-4xl font-bold mb-8 italic">Let's Enjoy with Gunil!</h3>
                <p class="text-slate-400 mb-12 max-w-lg mx-auto leading-relaxed">
                    最新のイベント情報や新着アイテムの通知を受け取りませんか？<br>
                    愛犬との暮らしがもっと楽しくなる情報をお届けします。
                </p>
                <button class="bg-white text-slate-900 px-8 py-3 rounded-full font-bold hover:bg-orange-500 hover:text-white transition-colors">
                    お問い合わせはこちら
                </button>
            </div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-orange-500 rounded-full opacity-20 blur-3xl"></div>
        </div>
    </div>
@endsection