{{--
  住所入力セクション（共通パーシャル）
  変数: $user (App\Models\User), $saved (array)
--}}
<div class="mb-8" x-data="{
    useProfile: {{ ($user->postal_code && empty($saved['postal_code'])) || (!empty($saved['postal_code']) && $saved['postal_code'] === $user->postal_code) ? 'true' : 'false' }},
    fillFromProfile() {
        if (!this.useProfile) return;
        this.$refs.shippingName.value  = {{ json_encode($user->name) }};
        this.$refs.postalCode.value    = {{ json_encode($user->postal_code ?? '') }};
        this.$refs.prefecture.value    = {{ json_encode($user->prefecture ?? '') }};
        this.$refs.city.value          = {{ json_encode($user->city ?? '') }};
        this.$refs.addressLine.value   = {{ json_encode($user->address_line ?? '') }};
        this.$refs.phone.value         = {{ json_encode($user->phone ?? '') }};
    }
}" x-init="fillFromProfile()">

    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">配送先住所</p>

    {{-- 切替トグル --}}
    <div class="grid grid-cols-2 gap-3 mb-5">
        <label class="cursor-pointer">
            <input type="radio" class="sr-only peer" name="_address_mode" value="profile"
                   x-model="useProfile" :value="true"
                   @change="fillFromProfile()"
                   {{ $user->postal_code ? '' : 'disabled' }}>
            <div class="border-2 rounded-2xl px-4 py-3 text-center text-sm transition
                        peer-checked:border-orange-500 peer-checked:bg-orange-50
                        {{ !$user->postal_code ? 'opacity-40 cursor-not-allowed border-slate-100' : 'border-slate-200 hover:border-orange-300' }}">
                <div class="text-xl mb-1">👤</div>
                <div class="font-bold text-slate-900">マイページから取得</div>
                @if($user->postal_code)
                    <div class="text-xs text-slate-400 mt-0.5 truncate">〒{{ $user->postal_code }} {{ $user->prefecture }}{{ $user->city }}</div>
                @else
                    <div class="text-xs text-orange-400 mt-0.5">住所未登録</div>
                @endif
            </div>
        </label>
        <label class="cursor-pointer">
            <input type="radio" class="sr-only peer" name="_address_mode" value="manual"
                   x-model="useProfile" :value="false">
            <div class="border-2 rounded-2xl px-4 py-3 text-center text-sm transition
                        peer-checked:border-slate-900 peer-checked:bg-slate-50
                        border-slate-200 hover:border-slate-400">
                <div class="text-xl mb-1">✏️</div>
                <div class="font-bold text-slate-900">直接入力する</div>
                <div class="text-xs text-slate-400 mt-0.5">今回のみ別住所</div>
            </div>
        </label>
    </div>

    {{-- 住所フォーム（常にDOM上に残す・マイページ取得時はreadonly） --}}
    <div class="space-y-3 bg-slate-50 rounded-2xl p-4 border border-slate-100">
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">お名前（宛名） <span class="text-red-500">*</span></label>
            <input type="text" name="shipping_name" x-ref="shippingName"
                   value="{{ old('shipping_name', $saved['shipping_name'] ?? $user->name) }}"
                   :readonly="useProfile"
                   :class="useProfile ? 'bg-orange-50 text-slate-500' : 'bg-white'"
                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition"
                   required maxlength="100" placeholder="例：山田 花子">
            @error('shipping_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">郵便番号 <span class="text-red-500">*</span></label>
                <input type="text" name="postal_code" x-ref="postalCode"
                       value="{{ old('postal_code', $saved['postal_code'] ?? $user->postal_code) }}"
                       :readonly="useProfile"
                       :class="useProfile ? 'bg-orange-50 text-slate-500' : 'bg-white'"
                       class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition"
                       required maxlength="8" placeholder="123-4567">
                @error('postal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">電話番号 <span class="text-red-500">*</span></label>
                <input type="text" name="phone" x-ref="phone"
                       value="{{ old('phone', $saved['phone'] ?? $user->phone) }}"
                       :readonly="useProfile"
                       :class="useProfile ? 'bg-orange-50 text-slate-500' : 'bg-white'"
                       class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition"
                       required maxlength="20" placeholder="090-0000-0000">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">都道府県 <span class="text-red-500">*</span></label>
            <input type="text" name="prefecture" x-ref="prefecture"
                   value="{{ old('prefecture', $saved['prefecture'] ?? $user->prefecture) }}"
                   :readonly="useProfile"
                   :class="useProfile ? 'bg-orange-50 text-slate-500' : 'bg-white'"
                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition"
                   required maxlength="20" placeholder="東京都">
            @error('prefecture') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">市区町村 <span class="text-red-500">*</span></label>
            <input type="text" name="city" x-ref="city"
                   value="{{ old('city', $saved['city'] ?? $user->city) }}"
                   :readonly="useProfile"
                   :class="useProfile ? 'bg-orange-50 text-slate-500' : 'bg-white'"
                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition"
                   required maxlength="100" placeholder="渋谷区">
            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 mb-1">番地・建物名 <span class="text-red-500">*</span></label>
            <input type="text" name="address_line" x-ref="addressLine"
                   value="{{ old('address_line', $saved['address_line'] ?? $user->address_line) }}"
                   :readonly="useProfile"
                   :class="useProfile ? 'bg-orange-50 text-slate-500' : 'bg-white'"
                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition"
                   required maxlength="200" placeholder="1-2-3 ○○マンション101号室">
            @error('address_line') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- マイページ取得モード時のリンク --}}
        <p x-show="useProfile" class="text-xs text-slate-400 pt-1">
            住所を変更するには
            <a href="{{ route('mypage') }}" class="text-orange-500 font-bold hover:underline" target="_blank">マイページ</a>
            で更新してください。
        </p>
    </div>
</div>
