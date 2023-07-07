<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCatetgory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request){
        $request->validate(['name'=>'required|string|max:20']);
        ProductCatetgory::create($request->all());
        return $this->sendMesssage('Added new category');
    }

    public function update(Request $request,ProductCatetgory $category){
        $request->validate(['name'=>'string|max:20']);
        $category->update($request->all());
        return $this->sendMesssage('Updated category');
    }

    public function destroy(ProductCatetgory $category){
        $category->delete();
        return $this->sendMesssage('Deleted category');
    }

    public function show(ProductCatetgory $category){
        return $category->product;
    }
}
