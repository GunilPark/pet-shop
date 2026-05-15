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

    {{-- 住所情報 --}}
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-900">📦 配送先住所</h2>
        </div>

        @if(session('address_updated'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-3 rounded-2xl mb-4 font-bold text-sm">
                ✅ 住所を更新しました
            </div>
        @endif

        <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm p-6" x-data="{ editing: false }">
            {{-- 表示モード --}}
            <div x-show="!editing">
                @if($user->postal_code || $user->city)
                    <div class="space-y-1 text-sm text-slate-700 mb-4">
                        <p>〒{{ $user->postal_code }}</p>
                        <p>{{ $user->prefecture }}{{ $user->city }}</p>
                        <p>{{ $user->address_line }}</p>
                        @if($user->phone) <p>📞 {{ $user->phone }}</p> @endif
                    </div>
                @else
                    <p class="text-slate-400 text-sm mb-4">住所が登録されていません。注文時に毎回入力が必要です。</p>
                @endif
                <button type="button" @click="editing = true"
                        class="text-sm font-bold text-orange-500 hover:text-orange-600 transition border border-orange-300 px-4 py-2 rounded-xl">
                    ✏️ 住所を{{ $user->postal_code ? '変更する' : '登録する' }}
                </button>
            </div>

            {{-- 編集モード --}}
            <div x-show="editing" x-cloak>
                <form method="POST" action="{{ route('mypage.address.update') }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">郵便番号</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                                   placeholder="123-4567" maxlength="8"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">電話番号</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   placeholder="090-0000-0000" maxlength="20"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">都道府県</label>
                        <input type="text" name="prefecture" value="{{ old('prefecture', $user->prefecture) }}"
                               placeholder="東京都" maxlength="20"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">市区町村</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                               placeholder="渋谷区" maxlength="100"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">番地・建物名</label>
                        <input type="text" name="address_line" value="{{ old('address_line', $user->address_line) }}"
                               placeholder="1-2-3 ○○マンション101号室" maxlength="200"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-orange-500 transition">
                            保存する
                        </button>
                        <button type="button" @click="editing = false"
                                class="text-slate-400 px-4 py-2 rounded-xl text-sm hover:text-slate-600 transition border border-slate-200">
                            キャンセル
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

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
                                    @if($order->dogProfile) {{ $order->dogProfile->name }} · @endif
                                    {{ $order->ordered_at->format('Y/m/d') }}
                                    @if($order->is_consultation) · <span class="text-orange-400 font-bold">相談中</span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            @php
                                $statusMap = [
                                    'pending'    => ['bg-yellow-100', 'text-yellow-700', '受付中'],
                                    'reviewing'  => ['bg-yellow-100', 'text-yellow-700', '確認中'],
                                    'confirmed'  => ['bg-green-50',   'text-green-600',  '注文確定'],
                                    'processing' => ['bg-blue-50',    'text-blue-600',   '制作中'],
                                    'shipping'   => ['bg-purple-50',  'text-purple-600', '配送中'],
                                    'completed'  => ['bg-slate-100',  'text-slate-600',  '完了'],
                                    'rejected'   => ['bg-red-100',    'text-red-600',    'キャンセル'],
                                ];
                                $s = $statusMap[$order->processing_status->value] ?? ['bg-slate-100', 'text-slate-500', $order->processing_status->getLabel()];
                                // 発送済み→配達完了
                                if ($order->order_status->value === 'delivered') {
                                    $s = ['bg-green-100', 'text-green-700', '配達完了'];
                                }
                            @endphp
                            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $s[0] }} {{ $s[1] }}">
                                {{ $s[2] }}
                            </span>
                            @if($order->is_consultation)
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-orange-50 text-orange-500">相談あり</span>
                            @endif
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
                                @if($apply->dogProfile) {{ $apply->dogProfile->name }} · @endif
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
