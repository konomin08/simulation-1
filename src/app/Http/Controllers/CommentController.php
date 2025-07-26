<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->back()
                ->withErrors(['comment' => 'コメントを投稿するには ログイン が必要です。'])
                ->withInput();
        }

        $product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('comment'),
        ]);

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }



    // public function comments()
    // {
    // return $this->hasMany(Comment::class);
    // }

}
