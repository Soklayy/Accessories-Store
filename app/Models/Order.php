<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'payment_status',
        'total',
    ];

    public function orderItem(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

}
