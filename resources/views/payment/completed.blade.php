@extends('layouts.app')
@section('title', 'お支払い完了 | INU GOODS')
@section('content')
<div class="container mx-auto px-6 py-24 max-w-md text-center">
    <div class="text-6xl mb-6">🎉</div>
    <h1 class="text-3xl font-black text-slate-900 mb-4">お支払いが完了しました</h1>
    <p class="text-slate-400 mb-2">注文番号 #{{ $order->id }}</p>
    <p class="text-slate-500 text-sm leading-relaxed mb-10">
        ありがとうございます。製作・発送の準備を開始いたします。<br>
        発送時にメールにてお知らせいたします。
    </p>
    <a href="/" class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-bold hover:bg-orange-500 transition">
        トップページへ戻る
    </a>
</div>
@endsection
