<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LikeController extends Controller
{
    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->likedProducts()->where('product_id', $product->id)->exists()) {
            $user->likedProducts()->detach($product->id);
        } else {
            $user->likedProducts()->attach($product->id);
        }

        return back()->with('success', 'いいねを更新しました！');
    }
}
