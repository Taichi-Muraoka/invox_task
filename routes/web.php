<?php

use Illuminate\Support\Facades\Route;

// ログイン機能がないため、indexに飛ばす
Route::get('/',  [App\Http\Controllers\IndexController::class, 'redirectIndex'])->name('redirect_index');

// index
Route::get('/index', [App\Http\Controllers\IndexController::class, 'index'])->name('index');

// 登録バリデーション
Route::post('/index/vd_input', [App\Http\Controllers\IndexController::class, 'validationForInput'])->name('index-vd_input');

// 登録処理
Route::post('/index/create', [App\Http\Controllers\IndexController::class, 'create'])->name('index-create');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
