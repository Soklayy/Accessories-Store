<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total'
    ];

    public function cartItem(){
        return $this->hasMany(CartItem::class,'shopping_id','id');
    }
}
