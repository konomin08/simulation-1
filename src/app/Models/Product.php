<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'condition',
        'image_path',
        'user_id',
        'category_id'
    ];

    protected $casts = [
        'price' => 'integer',
        'user_id' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
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

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function getDisplayImagePathAttribute(): ?string
    {
        return $this->image_path ?? $this->img_url ?? $this->image ?? null;
    }

    public function getDisplayImageUrlAttribute(): ?string
    {
        if (!$this->display_image_path) return null;

        if (str_starts_with($this->display_image_path, 'storage/')) {
            return asset($this->display_image_path);
        }
        return asset('storage/' . ltrim($this->display_image_path, '/'));
    }
}
