<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 認証必須ルート
Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/mypage', [AuthController::class, 'index'])->name('mypage');

    // 購入処理と購入画面
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');

    // いいね機能
    Route::post('/item/{product}/like', [LikeController::class, 'toggle'])->name('like.toggle');
});

Route::get('/', [ProductController::class, 'index'])->name('index');
Route::get('/mylist', [ProductController::class, 'mylist'])->name('mylist');

Route::get('/register', [RegisterController::class, 'show'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::post('/item/{product}/comment', [CommentController::class, 'store'])->name('comment.store');

// 出品画面
Route::get('/sell', function () {
    return view('sell');
})->name('sell');

// マイページ画面
Route::get('/mypage', function () {
    return view('mypage');
})->name('mypage');
