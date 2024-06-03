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
        return $this->belongsTo(Waiter::class);  // Pretpostavljajući da postoji model Waiter
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
