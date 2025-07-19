<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('comment'),
        ]);

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }
    public function comments()
    {
    return $this->hasMany(Comment::class);
    }

}
