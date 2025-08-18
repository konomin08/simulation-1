<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function show($item_id)
    {
        $product = Product::with(['comments.user', 'categories'])
            ->withCount('comments')
            ->findOrFail($item_id);

        $user = Auth::user();

        return view('item.show', compact('product'));

    }
}
