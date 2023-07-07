<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Shopping;
use Illuminate\Support\Facades\Gate;


class ShoppingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shopping = Auth()->user()->shopping;
        return $this->sendReponse([
            'id' => $shopping->id,
            'user_id' => $shopping->user_id,
            'total' => $shopping->total,
            "created_at" => $shopping->created_at,
            "updated_at" => $shopping->updated_at,
            "cart_item" => $shopping->cartItem,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Product $product)
    {
        $shopping = Auth()->user()->shopping;
        $cart=$shopping->cartItem->where('product_id',$product->id);

        if (count($cart)!==0) return $this->sendMesssage('Exist in cart',422);

        //add product to cart
        CartItem::create([
            'shopping_id'   => $shopping->id,
            'product_id' => $product->id,
            'quantity'  => 1,
        ]);

        $newShopping = Shopping::find($shopping->id)->first();
        $total = 0;
        foreach ($newShopping->cartItem as $item) {
            $total += $item->product->price*(1-$item->product->discount->discount_percent/100)* $item->quantity;
        }

        $newShopping->update([
            'total' => $total
        ]);

        return $this->sendMesssage('Added item');

    }

    //increase
    public function increase(CartItem $cart)
    {

        Gate::authorize('shopping-cart-owner', $cart);
        $shopping = Auth()->user()->shopping;
        $increase = $cart->quantity + 1;
        $cart->update([
            'quantity' => $increase,
        ]);

        $total = 0;
        foreach ($shopping->cartItem as $item) {
            $total += $item->product->price * $item->quantity;
        }

        $shopping->update([
            'total' => $total
        ]);

        return $this->sendMesssage('Item increased');
    }

    //decrease item
    public function decrease(CartItem $cart)
    {
        Gate::authorize('shopping-cart-owner',$cart);
        $shopping = Auth()->user()->shopping;

        if($cart->quantity === 1) $cart-> delete();

        $cart->update([
            'quantity' => $cart->quantity -1,
        ]);

        $total = 0;
        foreach ($shopping->cartItem as $item) {
            $total += $item->product->price * $item->quantity;
        }

        $shopping->update([
            'total' => $total
        ]);

        return $this->sendMesssage('Item decreased');
    }

    //deleted item from cart
    public function removeItem(CartItem $cart)
    {
        $shopping = Auth()->user()->shopping;
        Gate::authorize('shopping-cart-owner', $cart);
        $cart->delete();
        $total = 0;
        foreach ($shopping->cartItem as $item) {
            $total += $item->product->price * $item->quantity;
        }

        $shopping->update([
            'total' => $total
        ]);
        return $this->sendMesssage('Remove item succeed');
    }

}
