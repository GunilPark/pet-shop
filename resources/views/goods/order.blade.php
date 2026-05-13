@extends('layouts.app')

@section('title', '商品購入申請 | GUNIL PET SHOP')

@section('content')
<div class="container mx-auto px-6 py-16 max-w-2xl">

    <div class="mb-8">
        <a href="/goods" class="text-slate-400 text-sm hover:text-orange-500 transition">← グッズ一覧に戻る</a>
    </div>

    <div class="bg-slate-50 rounded-[32px] p-8 mb-8 border border-slate-100 flex gap-6 items-center">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}"
                 class="w-24 h-24 rounded-2xl object-cover" alt="{{ $item->name }}">
        @else
            <div class="w-24 h-24 rounded-2xl bg-slate-200 flex items-center justify-center text-4xl">🐾</div>
        @endif
        <div>
            <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Item</span>
            <h2 class="text-2xl font-black text-slate-900 mt-1">{{ $item->name }}</h2>
            <div class="text-2xl font-black text-orange-500 mt-1">¥{{ number_format($item->price) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
        <h3 class="text-xl font-bold text-slate-900 mb-2">購入申請フォーム</h3>
        <p class="text-slate-400 text-sm mb-8">愛犬の画像をアップロードしてください。管理者が確認後、加工を開始します。</p>

        @if($dogProfiles->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <div class="text-4xl mb-4">🐶</div>
                <p class="font-bold">犬のプロフィールが登録されていません。</p>
                <p class="text-sm mt-2">マイページから先にプロフィールを登録してください。</p>
                <a href="{{ route('mypage') }}" class="inline-block mt-6 bg-orange-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-600 transition">マイページへ</a>
            </div>
        @else
            <form method="POST" action="{{ route('goods.order.store', $item) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">対象の犬を選択</label>
                    <div class="grid gap-3">
                        @foreach($dogProfiles as $dog)
                            <label class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer hover:border-orange-300 transition
                                {{ old('dog_profile_id') == $dog->id ? 'border-orange-500 bg-orange-50' : 'border-slate-100' }}">
                                <input type="radio" name="dog_profile_id" value="{{ $dog->id }}"
                                    {{ old('dog_profile_id') == $dog->id ? 'checked' : '' }}
                                    class="accent-orange-500 w-5 h-5">
                                @if($dog->profile_image)
                                    <img src="{{ asset('storage/' . $dog->profile_image) }}"
                                         class="w-12 h-12 rounded-full object-cover" alt="{{ $dog->name }}">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-2xl">🐶</div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900">{{ $dog->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $dog->breed }} @if($dog->birthday)· {{ $dog->birthday->age }}歳@endif</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('dog_profile_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-3">愛犬の画像をアップロード</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-orange-300 transition">
                        <div class="text-3xl mb-2">📷</div>
                        <input type="file" name="uploaded_image" accept="image/*"
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200">
                        <p class="text-xs text-slate-400 mt-2">JPG, PNG 対応 / 最大5MB</p>
                    </div>
                    @error('uploaded_image')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-500 transition shadow-lg">
                    購入申請する
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
