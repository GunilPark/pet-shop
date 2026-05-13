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
        <div class="flex flex-wrap justify-center gap-4 mb-16">
            <button class="bg-orange-500 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-orange-100">すべて</button>
            <button class="bg-white text-slate-600 px-6 py-2 rounded-full font-bold border border-slate-100 hover:bg-orange-50 transition">フード</button>
            <button class="bg-white text-slate-600 px-6 py-2 rounded-full font-bold border border-slate-100 hover:bg-orange-50 transition">おもちゃ</button>
            <button class="bg-white text-slate-600 px-6 py-2 rounded-full font-bold border border-slate-100 hover:bg-orange-50 transition">アクセサリー</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
            
            <div class="group">
                <div class="relative bg-white rounded-[40px] aspect-square mb-6 overflow-hidden border border-slate-100 shadow-sm transition-all group-hover:shadow-2xl group-hover:-translate-y-2">
                    <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl group-hover:bg-orange-50 transition-colors">
                        COMING SOON
                    </div>
                    <div class="absolute top-6 left-6">
                        <span class="bg-white/90 backdrop-blur text-orange-600 text-[10px] font-black px-3 py-1 rounded-full shadow-sm">PREMIUM</span>
                    </div>
                </div>
                <div class="px-4">
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-500 transition-colors">無添加・国産鹿肉ジャーキー</h3>
                    <p class="text-slate-400 text-sm mb-4 italic">素材本来の旨味を凝縮した健康おやつ</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black text-slate-900">¥1,200 <span class="text-sm font-medium text-slate-400">(税込)</span></span>
                        <button class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-orange-500 transition-colors shadow-lg">＋</button>
                    </div>
                </div>
            </div>

            <div class="group">
                <div class="relative bg-white rounded-[40px] aspect-square mb-6 overflow-hidden border border-slate-100 shadow-sm transition-all group-hover:shadow-2xl group-hover:-translate-y-2">
                    <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl group-hover:bg-orange-50 transition-colors">
                        COMING SOON
                    </div>
                    <div class="absolute top-6 left-6">
                        <span class="bg-slate-900/90 backdrop-blur text-white text-[10px] font-black px-3 py-1 rounded-full shadow-sm">HANDMADE</span>
                    </div>
                </div>
                <div class="px-4">
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-500 transition-colors">本革イタリアンレザー・リード</h3>
                    <p class="text-slate-400 text-sm mb-4 italic">使うほどに馴染む、一生モノの品質</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black text-slate-900">¥5,800 <span class="text-sm font-medium text-slate-400">(税込)</span></span>
                        <button class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-orange-500 transition-colors shadow-lg">＋</button>
                    </div>
                </div>
            </div>

            <div class="group">
                <div class="relative bg-white rounded-[40px] aspect-square mb-6 overflow-hidden border border-slate-100 shadow-sm transition-all group-hover:shadow-2xl group-hover:-translate-y-2">
                    <div class="absolute inset-0 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl group-hover:bg-orange-50 transition-colors">
                        COMING SOON
                    </div>
                </div>
                <div class="px-4">
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-500 transition-colors">オーガニックコットン・ベッド</h3>
                    <p class="text-slate-400 text-sm mb-4 italic">最高の眠りを提供する、洗える贅沢ベッド</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black text-slate-900">¥8,900 <span class="text-sm font-medium text-slate-400">(税込)</span></span>
                        <button class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-orange-500 transition-colors shadow-lg">＋</button>
                    </div>
                </div>
            </div>

        </div>
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