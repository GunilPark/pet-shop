<?php

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
