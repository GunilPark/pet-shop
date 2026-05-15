@extends('layouts.app')
@section('title', 'リンク期限切れ | INU GOODS')
@section('content')
<div class="container mx-auto px-6 py-24 max-w-md text-center">
    <div class="text-6xl mb-6">⏰</div>
    <h1 class="text-3xl font-black text-slate-900 mb-4">リンクの有効期限が切れています</h1>
    <p class="text-slate-500 text-sm leading-relaxed mb-10">
        お支払いリンクの有効期限が過ぎました。<br>
        お手数ですが、担当者までご連絡ください。
    </p>
    <a href="/" class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-bold hover:bg-orange-500 transition">
        トップページへ戻る
    </a>
</div>
@endsection
