@extends('layouts.app')
@section('title', 'シルエット商品 注文 | INU GOODS')
@section('content')
<div class="container mx-auto px-6 py-16 max-w-xl">
    <div class="mb-8">
        <a href="/goods" class="text-slate-400 text-sm hover:text-orange-500 transition">← グッズ一覧に戻る</a>
    </div>

    <div class="bg-slate-50 rounded-[32px] p-6 mb-8 flex gap-5 items-center border border-slate-100">
        @if($item->thumbnail_image)
            <img src="{{ asset('storage/' . $item->thumbnail_image) }}" class="w-20 h-20 rounded-2xl object-cover">
        @else
            <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">🐕</div>
        @endif
        <div>
            <p class="text-xs text-slate-400 font-bold uppercase">Silhouette Item</p>
            <h2 class="text-xl font-black text-slate-900">{{ $item->name }}</h2>
            <p class="text-orange-500 font-black text-lg">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    @if($item->silhouette_guide)
        <div class="bg-blue-50 rounded-2xl p-5 mb-8 text-sm text-blue-700 border border-blue-100">
            📷 <strong>撮影ガイド</strong><br>
            <span class="mt-1 block">{{ $item->silhouette_guide }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
        <form method="POST" action="{{ route('goods.order.preview', $item) }}" enctype="multipart/form-data" x-data="silhouetteForm()">
            @csrf

            {{-- 犬選択 --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">対象の犬を選択 <span class="text-red-500">*</span></label>
                @if($dogProfiles->isEmpty())
                    <div class="bg-orange-50 rounded-2xl p-5 text-center text-orange-600 text-sm font-bold">
                        犬のプロフィールが未登録です。
                        <a href="{{ route('dog-profile.create') }}" class="underline ml-1">今すぐ登録する</a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($dogProfiles as $dog)
                            <label class="flex items-center gap-4 p-4 border-2 rounded-2xl cursor-pointer hover:border-orange-300 transition
                                {{ old('dog_profile_id') == $dog->id ? 'border-orange-500 bg-orange-50' : 'border-slate-100' }}"
                                @click="selectDog({{ json_encode(['id' => $dog->id, 'name' => $dog->name, 'breed' => $dog->breed, 'birthday' => $dog->birthday?->format('Y.m.d'), 'image' => $dog->profile_image ? asset('storage/'.$dog->profile_image) : null]) }})">
                                <input type="radio" name="dog_profile_id" value="{{ $dog->id }}"
                                    {{ old('dog_profile_id') == $dog->id ? 'checked' : '' }}
                                    class="accent-orange-500 w-5 h-5">
                                @if($dog->profile_image)
                                    <img src="{{ asset('storage/' . $dog->profile_image) }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-2xl">🐶</div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900">{{ $dog->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $dog->breed }} @if($dog->birthday)· {{ $dog->birthday->format('Y.m.d') }}@endif</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
                @error('dog_profile_id') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- 写真選択 --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">使用する写真</label>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:border-orange-300 transition border-slate-100"
                           :class="useProfileImage === 'yes' ? 'border-orange-500 bg-orange-50' : ''">
                        <input type="radio" name="use_profile_image" value="yes" x-model="useProfileImage"
                               class="accent-orange-500 w-5 h-5" {{ old('use_profile_image', 'yes') === 'yes' ? 'checked' : '' }}>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">プロフィール写真を使用</div>
                            <div class="text-xs text-slate-400">登録済みのプロフィール画像をそのまま使用</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:border-orange-300 transition border-slate-100"
                           :class="useProfileImage === 'no' ? 'border-orange-500 bg-orange-50' : ''">
                        <input type="radio" name="use_profile_image" value="no" x-model="useProfileImage"
                               class="accent-orange-500 w-5 h-5" {{ old('use_profile_image') === 'no' ? 'checked' : '' }}>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">新しい写真をアップロード</div>
                            <div class="text-xs text-slate-400">横向き全身写真を推奨</div>
                        </div>
                    </label>
                </div>

                <div x-show="useProfileImage === 'no'" x-cloak class="mt-4">
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-orange-300 transition">
                        <div class="text-2xl mb-2">📷</div>
                        <input type="file" name="uploaded_image" accept="image/*"
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-bold file:bg-orange-100 file:text-orange-600">
                        <p class="text-xs text-slate-400 mt-2">JPG・PNG / 最大10MB</p>
                    </div>
                </div>
                @error('uploaded_image') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- テキスト --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">商品に入れるテキスト</label>
                <div class="space-y-3 mb-4">
                    <label class="flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:border-orange-300 transition border-slate-100"
                           :class="useProfileText === 'yes' ? 'border-orange-500 bg-orange-50' : ''">
                        <input type="radio" name="use_profile_text" value="yes" x-model="useProfileText"
                               class="accent-orange-500 w-5 h-5" {{ old('use_profile_text', 'yes') === 'yes' ? 'checked' : '' }}>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">プロフィール情報をそのまま使用</div>
                            <div class="text-xs text-slate-400" x-text="selectedDog ? `${selectedDog.name} / ${selectedDog.breed || ''}` : 'まず犬を選択してください'"></div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:border-orange-300 transition border-slate-100"
                           :class="useProfileText === 'no' ? 'border-orange-500 bg-orange-50' : ''">
                        <input type="radio" name="use_profile_text" value="no" x-model="useProfileText"
                               class="accent-orange-500 w-5 h-5" {{ old('use_profile_text') === 'no' ? 'checked' : '' }}>
                        <div class="font-bold text-slate-900 text-sm">カスタマイズする</div>
                    </label>
                </div>

                <div x-show="useProfileText === 'no'" x-cloak class="space-y-3 pl-2">
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">名前 <span class="text-red-500">*</span></label>
                        <input type="text" name="custom_name" value="{{ old('custom_name') }}" maxlength="50"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">犬種</label>
                        <input type="text" name="custom_breed" value="{{ old('custom_breed') }}" maxlength="50"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 block mb-1">誕生日</label>
                        <input type="text" name="custom_birthday" value="{{ old('custom_birthday') }}" maxlength="20"
                               placeholder="例：2020.05.03"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                </div>
            </div>

            {{-- ロゴテキスト --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">ロゴ文字・追加文句（任意）</label>
                <input type="text" name="logo_text" value="{{ old('logo_text') }}" maxlength="30"
                       placeholder="例：LOVE POCHI / Forever"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <p class="text-xs text-slate-400 mt-1">30文字以内。商品デザインに合わせて配置します。</p>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-500 transition shadow-lg">
                確認・プレビューへ進む →
            </button>
        </form>
    </div>
</div>

<script>
function silhouetteForm() {
    return {
        useProfileImage: '{{ old('use_profile_image', 'yes') }}',
        useProfileText: '{{ old('use_profile_text', 'yes') }}',
        selectedDog: null,
        selectDog(dog) {
            this.selectedDog = dog;
        }
    }
}
</script>
@endsection
