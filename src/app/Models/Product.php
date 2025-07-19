<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
    return $this->hasMany(Comment::class);
    }

    public function likedUsers()
    {
    return $this->belongsToMany(
        \App\Models\User::class,
        'likes',
        'product_id',
        'user_id'
    )->withTimestamps();
    }
}