<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 商品一覧（おすすめ）
    public function index(Request $request)
    {
        $query = Product::query();

        if (Auth::check()) {
            $query->where(function ($q) {
                $q->whereNull('user_id')
                    ->orWhere('user_id', '!=', Auth::id());
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->get();

        return view('index', compact('products'))->with('page', 'recommend');
    }

    // マイリスト
    public function mylist(Request $request)
    {
        if (!Auth::check()) {
            // 未ログイン時は空の商品一覧を表示
            return view('index', ['products' => collect()])->with('page', 'mylist');
        }
    
        $user = Auth::user();
        $query = $user->likedProducts();
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
    
        $products = $query->get();
    
        return view('index', compact('products'))->with('page', 'mylist');
    }
    
    
}
