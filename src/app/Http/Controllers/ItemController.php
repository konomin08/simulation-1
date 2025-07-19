<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($item_id)
    {
        $product = Product::with(['comments.user', 'category'])->findOrFail($item_id);

        return view('item.show', compact('product'));
    }
}
