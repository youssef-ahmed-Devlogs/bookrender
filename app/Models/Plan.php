<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'book_number',
        'word_number',
        'paddle_product_id',
        'paddle_price_id',
        'is_free'
    ];
    protected $casts = [
        'status' => 'string',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // User.php


}
