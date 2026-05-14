@extends('layouts.app')

@section('title', 'マイページ | GUNIL PET SHOP')

@section('content')
<div class="container mx-auto px-6 py-16 max-w-4xl">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- ユーザー情報 --}}
    <div class="flex items-center gap-6 mb-12">
        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-3xl">👤</div>
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ $user->name }}</h1>
            <p class="text-slate-400 text-sm">{{ $user->email }}</p>
        </div>
    </div>

    {{-- 犬プロフィール一覧 --}}
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-900">🐶 愛犬プロフィール</h2>
            <a href="{{ route('dog-profile.create') }}"
               class="bg-orange-500 text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-orange-600 transition">
                ＋ 追加する
            </a>
        </div>

        @if($user->dogProfiles->isEmpty())
            <div class="bg-slate-50 rounded-[24px] p-8 text-center text-slate-400">
                <div class="text-4xl mb-3">🐾</div>
                <p>まだ犬のプロフィールが登録されていません。</p>
                <a href="{{ route('dog-profile.create') }}"
                   class="inline-block mt-4 bg-orange-500 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-orange-600 transition">
                    今すぐ登録する
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($user->dogProfiles as $dog)
                    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-6 flex items-center gap-5">
                        @if($dog->profile_image)
                            <img src="{{ asset('storage/' . $dog->profile_image) }}"
                                 class="w-16 h-16 rounded-full object-cover flex-shrink-0" alt="{{ $dog->name }}">
                        @else
                            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-3xl flex-shrink-0">🐶</div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-lg text-slate-900">{{ $dog->name }}</div>
                            <div class="text-sm text-slate-400">
                                {{ $dog->breed }}
                                @if($dog->birthday) · {{ $dog->birthday->age }}歳 @endif
                                @if($dog->gender) · {{ $dog->gender->getLabel() }} @endif
                            </div>
                            @if($dog->weight)
                                <div class="text-xs text-slate-400 mt-1">体重 {{ $dog->weight }}kg</div>
                            @endif
                        </div>
                        <a href="{{ route('dog-profile.edit', $dog) }}"
                           class="text-slate-400 hover:text-orange-500 transition text-sm font-bold flex-shrink-0">
                            編集
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 注文履歴 --}}
    <section class="mb-16">
        <h2 class="text-xl font-bold text-slate-900 mb-6">🛍 注文履歴</h2>

        @if($user->goodsOrders->isEmpty())
            <div class="bg-slate-50 rounded-[24px] p-8 text-center text-slate-400">
                <p>注文履歴はありません。</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($user->goodsOrders as $order)
                    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-xl">🦴</div>
                            <div>
                                <div class="font-bold text-slate-900">{{ $order->item->name }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $order->dogProfile->name }} ·
                                    {{ $order->ordered_at->format('Y/m/d') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <span class="text-xs font-bold px-3 py-1 rounded-full
                                {{ $order->order_status->getColor() === 'success' ? 'bg-green-100 text-green-700' :
                                   ($order->order_status->getColor() === 'warning' ? 'bg-yellow-100 text-yellow-700' :
                                   ($order->order_status->getColor() === 'danger' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600')) }}">
                                {{ $order->order_status->getLabel() }}
                            </span>
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-50 text-blue-600">
                                {{ $order->processing_status->getLabel() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- イベント申請履歴 --}}
    <section>
        <h2 class="text-xl font-bold text-slate-900 mb-6">📅 イベント申請履歴</h2>

        @if($user->eventApplies->isEmpty())
            <div class="bg-slate-50 rounded-[24px] p-8 text-center text-slate-400">
                <p>イベント申請履歴はありません。</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($user->eventApplies as $apply)
                    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-6 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900">{{ $apply->event->title }}</div>
                            <div class="text-xs text-slate-400">
                                {{ $apply->dogProfile->name }} ·
                                {{ $apply->event->started_at->format('Y/m/d') }} ·
                                申請日 {{ $apply->applied_at->format('Y/m/d') }}
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full
                            {{ $apply->apply_status->getColor() === 'success' ? 'bg-green-100 text-green-700' :
                               ($apply->apply_status->getColor() === 'warning' ? 'bg-yellow-100 text-yellow-700' :
                               ($apply->apply_status->getColor() === 'danger' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600')) }}">
                            {{ $apply->apply_status->getLabel() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection
