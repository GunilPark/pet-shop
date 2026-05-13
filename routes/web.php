<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventApplyController;
use App\Http\Controllers\GoodsOrderController;
use App\Models\DogGoodsEvent;
use App\Models\DogGoodsItem;
use Illuminate\Support\Facades\Route;

// TOPページ
Route::get('/', function () {
    $latestItems  = DogGoodsItem::active()->take(3)->get();
    $latestEvents = DogGoodsEvent::active()->upcoming()->orderBy('started_at')->take(2)->get();
    return view('welcome', compact('latestItems', 'latestEvents'));
});

// 犬RUNイベントページ
Route::get('/event', function () {
    $events = DogGoodsEvent::active()->orderBy('started_at')->get();
    return view('event', compact('events'));
});

// 犬グッズページ
Route::get('/goods', function () {
    $items = DogGoodsItem::active()->get();
    return view('goods', compact('items'));
});

// ダッシュボード（Breeze）
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// プロフィール（Breeze）
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// イベント参加申請（要ログイン）
Route::middleware('auth')->group(function () {
    Route::get('/event/{event}/apply', [EventApplyController::class, 'create'])->name('event.apply.create');
    Route::post('/event/{event}/apply', [EventApplyController::class, 'store'])->name('event.apply.store');

    // 商品購入申請（要ログイン）
    Route::get('/goods/{item}/order', [GoodsOrderController::class, 'create'])->name('goods.order.create');
    Route::post('/goods/{item}/order', [GoodsOrderController::class, 'store'])->name('goods.order.store');

    // マイページ
    Route::get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');
});

require __DIR__.'/auth.php';
