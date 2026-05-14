@extends('layouts.app')

@section('title', '犬プロフィール登録 | GUNIL PET SHOP')

@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl">

    <div class="mb-8">
        <a href="{{ route('mypage') }}" class="text-slate-400 text-sm hover:text-orange-500 transition">← マイページに戻る</a>
    </div>

    <h1 class="text-2xl font-black text-slate-900 mb-8">🐶 犬プロフィールを登録</h1>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
        <form method="POST" action="{{ route('dog-profile.store') }}" enctype="multipart/form-data">
            @csrf

            @include('dog-profile._form')

            <button type="submit"
                class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100 mt-4">
                登録する
            </button>
        </form>
    </div>
</div>
@endsection
