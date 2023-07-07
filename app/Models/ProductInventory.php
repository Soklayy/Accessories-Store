<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'instock'
    ];

    // public function product(){
    //     return $this->belongsTo($this,'inventory_id','id','id',Product::class);
    // }
}
