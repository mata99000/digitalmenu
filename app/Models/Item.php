<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'subcategory_id', 'name', 'description', 'price', 'comment', 'image' , 'type'];
    public function options()
    {
        return $this->hasMany(ItemOption::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
{
    return $this->belongsTo(Subcategory::class);
}

}
