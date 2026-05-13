@extends('layouts.app')

@section('title', 'イベント参加申請 | GUNIL PET SHOP')

@section('content')
<div class="container mx-auto px-6 py-16 max-w-2xl">

    <div class="mb-8">
        <a href="/event" class="text-slate-400 text-sm hover:text-orange-500 transition">← イベント一覧に戻る</a>
    </div>

    <div class="bg-orange-50 rounded-[32px] p-8 mb-8 border border-orange-100">
        <span class="text-orange-600 text-xs font-bold uppercase tracking-widest">Event</span>
        <h2 class="text-2xl font-black text-slate-900 mt-2 mb-2">{{ $event->title }}</h2>
        <div class="flex flex-wrap gap-4 text-sm text-slate-500 mt-4">
            <span>📅 {{ $event->started_at->format('Y年m月d日 H:i') }}</span>
            @if($event->location)
                <span>📍 {{ $event->location }}</span>
            @endif
            @if($event->max_capacity)
                <span>👥 定員 {{ $event->max_capacity }}組</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
        <h3 class="text-xl font-bold text-slate-900 mb-8">参加申請フォーム</h3>

        @if($dogProfiles->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <div class="text-4xl mb-4">🐶</div>
                <p class="font-bold">犬のプロフィールが登録されていません。</p>
                <p class="text-sm mt-2">マイページから先にプロフィールを登録してください。</p>
                <a href="{{ route('mypage') }}" class="inline-block mt-6 bg-orange-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-600 transition">マイページへ</a>
            </div>
        @else
            <form method="POST" action="{{ route('event.apply.store', $event) }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">参加する犬を選択</label>
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

                <button type="submit"
                    class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100">
                    参加申請する
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
