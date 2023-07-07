<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    public function show(Product $product){
        Gate::authorize('product-owner',$product);
        return $product->inventory;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryRequest $request, Product $product)
    {
        Gate::authorize('product-owner',$product);
        $request->validated();
        $inventory = $product->inventory();

        if(isset($request['quantity'])){
            $request['quantity'] += $inventory['quantity'];
        }
        $inventory->update($request->all());
        return $this->sendMesssage('Updated Inventory');
    }

}
