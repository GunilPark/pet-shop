<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\ProcessingStatus;
use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Http\Request;

class GoodsOrderController extends Controller
{
    public function create(DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $dogProfiles = auth()->user()->dogProfiles()->where('is_active', true)->get();

        return view('goods.order', compact('item', 'dogProfiles'));
    }

    public function store(Request $request, DogGoodsItem $item)
    {
        abort_if(! $item->is_active, 404);

        $request->validate([
            'dog_profile_id' => ['required', 'exists:dog_profiles,id'],
            'uploaded_image' => ['required', 'image', 'max:5120'],
        ]);

        $imagePath = $request->file('uploaded_image')->store('orders/uploaded', 'public');

        DogGoodsOrder::create([
            'user_id'           => auth()->id(),
            'dog_profile_id'    => $request->dog_profile_id,
            'item_id'           => $item->id,
            'order_status'      => OrderStatus::Pending,
            'processing_status' => ProcessingStatus::Pending,
            'uploaded_image'    => $imagePath,
            'ordered_at'        => now(),
        ]);

        return redirect()->route('mypage')->with('success', 'ご注文が完了しました！管理者が確認後、加工を開始します。');
    }
}
