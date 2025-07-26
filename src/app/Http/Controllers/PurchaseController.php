<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class PurchaseController extends Controller
{
    public function store(Request $request, $item_id)
    {
        $user = auth()->user();
    
        if (!$user) {
            \Log::warning('未ログインで購入処理が走りました');
            return redirect()->route('login')->with('error', '購入にはログインが必要です');
        }
    
        $product = Product::findOrFail($item_id);
    
        \Log::info('購入処理', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
        ]);
    
        return redirect()->route('purchase.show', ['item_id' => $product->id])
                         ->with('success', '購入手続きへ進みます');
    }
    

    public function show($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();
        $address = \App\Models\DeliveryAddress::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
    
        // ここに追加（商品ごとの配送先を取得）
        $address = \App\Models\DeliveryAddress::where('user_id', $user->id)
            ->where('product_id', $item_id)
            ->first();
    
        return view('purchase.show', compact('product', 'user', 'address'));
    }


}
