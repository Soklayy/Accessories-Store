<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'category_id',
        'inventory_id',
        'price',
        'discount_id',
        'image'
    ];

    public function category(){
        return $this->hasOne(category::class,'id','category_id');
    }

    public function inventory(){
        return $this->hasOne(ProductInventory::class,'id','inventory_id');
    }

    public function discount(){
        return $this->hasOne(Discount::class,'id','discount_id');
    }
}
