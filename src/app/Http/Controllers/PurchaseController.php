<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

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
        $user = auth()->user(); // ログインユーザー

        return view('purchase.show', compact('product', 'user'));
    }

}
