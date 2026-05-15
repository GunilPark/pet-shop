{{--
  ペット選択セクション（ネームタグ注文用パーシャル）
  変数: $user, $dogProfiles (Collection), $saved (array)
--}}
<div class="mb-8" x-data="{
    petMode: '{{ !empty($saved['dog_profile_id']) ? 'profile' : ($dogProfiles->isNotEmpty() ? 'profile' : 'manual') }}',
    selectedDogId: '{{ old('dog_profile_id', $saved['dog_profile_id'] ?? '') }}',
    dogs: {{ $dogProfiles->map(fn($d) => [
        'id'      => $d->id,
        'name'    => $d->name,
        'breed'   => $d->breed ?? '',
        'birthday'=> $d->birthday ? $d->birthday->format('Y.m.d') : '',
    ])->values()->toJson() }},
    get selectedDog() {
        return this.dogs.find(d => String(d.id) === String(this.selectedDogId)) ?? null;
    },
    fillFromDog() {
        if (this.petMode !== 'profile' || !this.selectedDog) return;
        this.$dispatch('fill-pet', {
            name:     this.selectedDog.name,
            breed:    this.selectedDog.breed,
            birthday: this.selectedDog.birthday,
        });
    }
}" @change-pet-mode.window="petMode = $event.detail">

    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ペット情報</p>

    {{-- 切替トグル --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        <label class="cursor-pointer">
            <input type="radio" class="sr-only peer" name="_pet_mode" value="profile"
                   x-model="petMode"
                   {{ $dogProfiles->isEmpty() ? 'disabled' : '' }}>
            <div class="border-2 rounded-2xl px-4 py-3 text-center text-sm transition
                        peer-checked:border-orange-500 peer-checked:bg-orange-50
                        {{ $dogProfiles->isEmpty() ? 'opacity-40 cursor-not-allowed border-slate-100' : 'border-slate-200 hover:border-orange-300' }}">
                <div class="text-xl mb-1">🐶</div>
                <div class="font-bold text-slate-900">登録ペットから選ぶ</div>
                @if($dogProfiles->isEmpty())
                    <div class="text-xs text-orange-400 mt-0.5">プロフィール未登録</div>
                @else
                    <div class="text-xs text-slate-400 mt-0.5">{{ $dogProfiles->count() }}頭登録済み</div>
                @endif
            </div>
        </label>
        <label class="cursor-pointer">
            <input type="radio" class="sr-only peer" name="_pet_mode" value="manual"
                   x-model="petMode">
            <div class="border-2 rounded-2xl px-4 py-3 text-center text-sm transition
                        peer-checked:border-slate-900 peer-checked:bg-slate-50
                        border-slate-200 hover:border-slate-400">
                <div class="text-xl mb-1">✏️</div>
                <div class="font-bold text-slate-900">直接入力する</div>
                <div class="text-xs text-slate-400 mt-0.5">他のペットなど</div>
            </div>
        </label>
    </div>

    {{-- プロフィール選択モード --}}
    <div x-show="petMode === 'profile'" class="space-y-3 mb-2">
        @if($dogProfiles->isNotEmpty())
            <div class="grid gap-2">
                @foreach($dogProfiles as $dog)
                    <label class="cursor-pointer">
                        <input type="radio" name="dog_profile_id" value="{{ $dog->id }}" class="sr-only peer"
                               x-model="selectedDogId"
                               @change="fillFromDog()"
                               {{ old('dog_profile_id', $saved['dog_profile_id'] ?? '') == $dog->id ? 'checked' : '' }}>
                        <div class="border-2 rounded-2xl px-4 py-3 flex items-center gap-4 transition
                                    peer-checked:border-orange-500 peer-checked:bg-orange-50 border-slate-200 hover:border-orange-300">
                            @if($dog->profile_image)
                                <img src="{{ asset('storage/' . $dog->profile_image) }}"
                                     class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-2xl flex-shrink-0">🐶</div>
                            @endif
                            <div>
                                <div class="font-bold text-slate-900">{{ $dog->name }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $dog->breed }}
                                    @if($dog->birthday) · {{ $dog->birthday->format('Y.m.d') }} @endif
                                    @if($dog->gender) · {{ $dog->gender->getLabel() }} @endif
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-slate-400">
                <a href="{{ route('dog-profile.create') }}" target="_blank" class="text-orange-500 font-bold hover:underline">＋ 新しく登録する</a>
            </p>
        @endif
    </div>

    {{-- 手動入力モード（dog_profile_id を空にする隠しフィールド） --}}
    <input x-show="petMode === 'manual'" type="hidden" name="dog_profile_id" value="">

</div>
