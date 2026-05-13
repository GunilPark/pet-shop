<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// TOPページ
Route::get('/', function () {
    return view('welcome');
});

// 犬RUNイベントページ
Route::get('/event', function () {
    return view('event');
});

// 犬グッズページ
Route::get('/goods', function () {
    return view('goods');
});
