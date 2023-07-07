<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function store(Request $request){

        $request->validate([
            'discount_percent'=> 'required|numeric|max:100',
            'active'          => 'required|boolean'
        ]);

        Discount::create($request->all());
        return $this->sendMesssage('Added new discount');
    }

    public function update(Request $request, Discount $discount){
        $request->validate([
            'discount_percent'=>"numeric|max:100",
            'active'=> 'boolean'
        ]);
        $discount->update($request->all());
        return $this->sendMesssage('Updated discount');
    }
}
