<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'waiter_id', 'total', 'status', 'delivered_at'
    ];

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id')->where('role', 'waiter');
    }
    public function orderedItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function orderItems()  // Ensure the function name matches the relationship name you are trying to access
    {
        return $this->hasMany(\App\Models\OrderItem::class);
        }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
