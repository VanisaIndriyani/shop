<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'shipping_address',
        'shipping_courier',
        'tracking_number',
        'shipping_note',
        'shipped_at',
        'payment_method',
        'payment_proof',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
