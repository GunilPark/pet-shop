<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Models\DogProfile;
use Illuminate\Http\Request;

class DogProfileController extends Controller
{
    public function create()
    {
        return view('dog-profile.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'breed'         => ['nullable', 'string', 'max:100'],
            'birthday'      => ['nullable', 'date'],
            'gender'        => ['required', 'in:male,female,unknown'],
            'weight'        => ['nullable', 'numeric', 'min:0', 'max:200'],
            'profile_image' => ['nullable', 'image', 'max:5120'],
            'memo'          => ['nullable', 'string'],
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('dog-profiles', 'public');
        }

        $data['user_id']   = auth()->id();
        $data['is_active'] = true;

        DogProfile::create($data);

        return redirect()->route('mypage')->with('success', '犬のプロフィールを登録しました！');
    }

    public function edit(DogProfile $dogProfile)
    {
        abort_if($dogProfile->user_id !== auth()->id(), 403);

        return view('dog-profile.edit', compact('dogProfile'));
    }

    public function update(Request $request, DogProfile $dogProfile)
    {
        abort_if($dogProfile->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'breed'         => ['nullable', 'string', 'max:100'],
            'birthday'      => ['nullable', 'date'],
            'gender'        => ['required', 'in:male,female,unknown'],
            'weight'        => ['nullable', 'numeric', 'min:0', 'max:200'],
            'profile_image' => ['nullable', 'image', 'max:5120'],
            'memo'          => ['nullable', 'string'],
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('dog-profiles', 'public');
        } else {
            unset($data['profile_image']);
        }

        $dogProfile->update($data);

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました！');
    }

    public function destroy(DogProfile $dogProfile)
    {
        abort_if($dogProfile->user_id !== auth()->id(), 403);

        $dogProfile->delete();

        return redirect()->route('mypage')->with('success', 'プロフィールを削除しました。');
    }
}
