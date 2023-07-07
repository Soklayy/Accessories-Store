<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ShoppingController;
use App\Http\Controllers\User\UserAddressController;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::group(['middleware'=>"auth:sanctum"],function(){
    //logout and me route
    Route::post('/logout',[AuthController::class,"logout"]);
    Route::get('/me',[AuthController::class,'me']);

    //admin
    Route::group(['middleware'=>'seller','prefix'=>'admin'],function(){
        Route::apiResource('product',ProductController::class);
        Route::match(['put', 'patch'], '/inventory/{product}',[InventoryController::class,'update']);
        Route::get('/inventory/{product}',[InventoryController::class,'show']);
    });

    //user
    Route::prefix('/user')->group(function(){
        Route::get('/shopping',[ShoppingController::class,'index'])->name('cart.index');
        Route::post('/shopping/{product}',[ShoppingController::class,'store'])->name('cart.addItem');
        Route::post('/shopping/increase-item/{cart}',[ShoppingController::class,'increase'])->name('cart.increase');
        Route::post('/shopping/decrease-item/{cart}',[ShoppingController::class,'decrease'])->name('cart.decrease');
        Route::delete('/shopping/remove-item/{cart}',[ShoppingController::class,'removeItem'])->name('cart.removeItem');
       
        //order
        Route::post('/order',[OrderController::class,'store']);
        Route::post('/checkout',[OrderController::class,'checkout']);

        //user address
        Route::match(['put', 'patch'],'/address',[UserAddressController::class,'update']);
    });
});


Route::post('webhook/{order}',[OrderController::class,'webhook'])->name('webhook');
Route::get('/product',[ProductController::class,'index']);
Route::get('/product/{product}',[ProductController::class,'show']);
Route::get('/category/{category}',[CategoryController::class,'show']);
