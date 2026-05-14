{{-- 共通フォームパーツ --}}

<div class="space-y-6">

    {{-- 名前 --}}
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-1">名前 <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $dogProfile->name ?? '') }}"
               class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-300"
               placeholder="例：ポチ">
        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- 犬種 --}}
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-1">犬種</label>
        <input type="text" name="breed" value="{{ old('breed', $dogProfile->breed ?? '') }}"
               class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-300"
               placeholder="例：トイプードル">
        @error('breed') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- 誕生日 + 性別 --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">誕生日</label>
            <input type="date" name="birthday" value="{{ old('birthday', isset($dogProfile) ? $dogProfile->birthday?->format('Y-m-d') : '') }}"
                   class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-300">
            @error('birthday') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">性別 <span class="text-red-500">*</span></label>
            <select name="gender"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-300">
                <option value="unknown" {{ old('gender', $dogProfile->gender->value ?? 'unknown') === 'unknown' ? 'selected' : '' }}>不明</option>
                <option value="male"    {{ old('gender', $dogProfile->gender->value ?? '') === 'male'    ? 'selected' : '' }}>オス</option>
                <option value="female"  {{ old('gender', $dogProfile->gender->value ?? '') === 'female'  ? 'selected' : '' }}>メス</option>
            </select>
            @error('gender') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- 体重 --}}
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-1">体重 (kg)</label>
        <input type="number" name="weight" step="0.1" value="{{ old('weight', $dogProfile->weight ?? '') }}"
               class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-300"
               placeholder="例：3.5">
        @error('weight') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- プロフィール画像 --}}
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">プロフィール画像</label>
        @if(isset($dogProfile) && $dogProfile->profile_image)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $dogProfile->profile_image) }}"
                     class="w-24 h-24 rounded-full object-cover border-2 border-orange-100" alt="現在の画像">
                <p class="text-xs text-slate-400 mt-1">現在の画像。新しい画像をアップロードすると上書きされます。</p>
            </div>
        @endif
        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-orange-300 transition">
            <div class="text-2xl mb-2">📷</div>
            <input type="file" name="profile_image" accept="image/*"
                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200">
            <p class="text-xs text-slate-400 mt-2">JPG, PNG / 最大5MB</p>
        </div>
        @error('profile_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- メモ --}}
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-1">メモ</label>
        <textarea name="memo" rows="3"
                  class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-300"
                  placeholder="アレルギーや注意事項など">{{ old('memo', $dogProfile->memo ?? '') }}</textarea>
        @error('memo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

</div>
