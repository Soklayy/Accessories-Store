<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $request->validated();

        if($request->has('image')){
            $image = $request->image;
            $name = date('YYYYmmddHis').'.'.$image->extension();
            $path = public_path('image');
            $image->move($path,$name);
        }

        $inventory = ProductInventory::create(['quantity' => 0]);

        Product::create([
            'user_id'     => Auth()->user()->id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price, 
            'category_id' => $request->category_id,
            'discount_id' => 1,
            'inventory_id'=> $inventory->id,  
            'image'       => 'image/'.$name,
        ]);

        return response([
            'Message' => 'Create product success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product->discount->discount_percent;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        Gate::authorize('product-owner',$product);
        $request->validated();
        $product->update($request->all());
        return $this->sendMesssage('Update success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('product-owner',$product);
        $product->inventory()->delete();
        $product->delete();
        return $this->sendMesssage('Deleted product');
    }
}
