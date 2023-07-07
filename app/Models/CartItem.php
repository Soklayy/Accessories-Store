<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopping_id',
        'product_id',
        'quantity'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
