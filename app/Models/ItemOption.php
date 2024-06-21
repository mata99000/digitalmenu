<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'name', 'type'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function price()
    {
        return $this->hasOne(OptionPrice::class);
    }
}
