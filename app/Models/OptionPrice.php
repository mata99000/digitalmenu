<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionPrice extends Model
{
    use HasFactory;
    protected $fillable = ['option_id', 'amount'];

    public function option()
    {
        return $this->belongsTo(ItemOption::class, 'option_id');
    }
}
