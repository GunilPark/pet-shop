<?php

namespace App\Http\Controllers;

use App\Enums\ApplyStatus;
use App\Models\DogGoodsEvent;
use App\Models\DogEventApply;
use Illuminate\Http\Request;

class EventApplyController extends Controller
{
    public function create(DogGoodsEvent $event)
    {
        abort_if(! $event->is_active, 404);

        $dogProfiles = auth()->user()->dogProfiles()->where('is_active', true)->get();

        return view('event.apply', compact('event', 'dogProfiles'));
    }

    public function store(Request $request, DogGoodsEvent $event)
    {
        abort_if(! $event->is_active, 404);

        $request->validate([
            'dog_profile_id' => ['required', 'exists:dog_profiles,id'],
        ]);

        $user = auth()->user();

        // 定員チェック
        if ($event->max_capacity !== null) {
            $approved = $event->approvedApplies()->count();
            abort_if($approved >= $event->max_capacity, 422, '定員に達しています。');
        }

        // 重複申請チェック
        $exists = DogEventApply::where('event_id', $event->id)
            ->where('dog_profile_id', $request->dog_profile_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['dog_profile_id' => 'すでに申請済みです。']);
        }

        DogEventApply::create([
            'event_id'       => $event->id,
            'user_id'        => $user->id,
            'dog_profile_id' => $request->dog_profile_id,
            'apply_status'   => ApplyStatus::Applied,
            'applied_at'     => now(),
        ]);

        return redirect()->route('mypage')->with('success', 'イベントへの参加申請が完了しました！');
    }
}
