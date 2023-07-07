<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Gate;

use App\Models\Product;
use App\Models\Shopping;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('product-owner',function(User $user,Product $product){
            return $user->id === $product->user_id;
        });

        Gate::define('shopping-cart-owner',function(User $user,CartItem $cartItem){
            $shopping = $user->shopping;
            return $shopping->id === $cartItem->shopping_id;
        });

        Gate::define('user-address-owner',function(User $user,UserAddress $userAddress){
            return $user->id === $userAddress->user_id ;
        });
    }
}
