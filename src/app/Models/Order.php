<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','product_id','amount','status','stripe_session_id','stripe_pi',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function product(){ return $this->belongsTo(Product::class); }
}


