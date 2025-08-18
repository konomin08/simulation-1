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
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\SellController;

Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::post('/mypage', [MypageController::class, 'update'])->name('mypage.update');

    // 配送先変更
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');

    // 購入処理
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show'); // ← これが「購入画面表示」
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store'); // ← 購入POST
    Route::post('/purchase/payment-method/{item_id}', [PurchaseController::class, 'setPaymentMethod'])->name('purchase.paymentMethod');

    // ★ 追加：Stripe Checkout の戻り先
    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/{item_id}/cancel',  [PurchaseController::class, 'cancel'])->name('purchase.cancel');

     // ★ 追加：支払い待ち(Pending)
    Route::get('/purchase/{item_id}/pending', [PurchaseController::class, 'pending'])->name('purchase.pending');
    // ★ 追加：Pending画面から「状態を確認」ボタン用
    Route::post('/purchase/{item_id}/check', [PurchaseController::class, 'checkStatus'])->name('purchase.check');

    // いいね
    Route::post('/item/{product}/like', [LikeController::class, 'toggle'])->name('like.toggle');
});

// 商品一覧・マイリスト
Route::get('/', [ProductController::class, 'index'])->name('index');
Route::get('/mylist', [ProductController::class, 'mylist'])->name('mylist');

// 会員登録・ログイン
Route::get('/register', [RegisterController::class, 'show'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// コメント
Route::post('/item/{product}/comment', [CommentController::class, 'store'])->name('comment.store');

// 出品画面
Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
// 出品保存
Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
// 出品完了ページ
Route::get('/sell/complete', [SellController::class, 'complete'])->name('sell.complete');
// 追加
// routes/web.php
Route::post('/sell/preview', [\App\Http\Controllers\SellController::class, 'preview'])
    ->name('sell.preview');

 // ★ 追加：Stripe Webhook（認証なし）
Route::post('/webhook/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('stripe.webhook');