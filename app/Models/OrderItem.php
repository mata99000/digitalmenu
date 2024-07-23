<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'item_id', 'quantity', 'price', 
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
    public function item()
    {
        return $this->belongsTo(Item::class);  // Pretpostavljajući da postoji model Item
    }
    public function orderItemoptions()
    {
        return $this->hasMany(OrderItemOption::class);
    }
}
