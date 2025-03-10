<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

// ログイン機能がないため、indexに飛ばす
Route::get('/',  [IndexController::class, 'redirectIndex'])->name('redirect_index');

// index
Route::get('/index', [IndexController::class, 'index'])->name('index');

// 登録時のバリデーション
Route::post('/index/vd_input', [IndexController::class, 'validationForInput'])->name('index-vd_input');

// 登録処理
Route::post('/index/create', [IndexController::class, 'create'])->name('index-create');