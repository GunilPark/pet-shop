@extends('layouts.app')

@section('title', '犬プロフィール編集 | GUNIL PET SHOP')

@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl">

    <div class="mb-8">
        <a href="{{ route('mypage') }}" class="text-slate-400 text-sm hover:text-orange-500 transition">← マイページに戻る</a>
    </div>

    <h1 class="text-2xl font-black text-slate-900 mb-8">🐶 プロフィールを編集</h1>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
        <form method="POST" action="{{ route('dog-profile.update', $dogProfile) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            @include('dog-profile._form', ['dogProfile' => $dogProfile])

            <div class="flex gap-3 mt-4">
                <button type="submit"
                    class="flex-1 bg-orange-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100">
                    更新する
                </button>

                <form method="POST" action="{{ route('dog-profile.destroy', $dogProfile) }}" class="flex-none"
                      onsubmit="return confirm('本当に削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-50 text-red-500 px-6 py-4 rounded-2xl font-bold hover:bg-red-100 transition">
                        削除
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
