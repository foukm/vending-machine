<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function add(){
        return view('seller.add');
    }

    public function edit(Request $request){
        return view('seller.update');
    }

    public function delete(Request $request){
        return view('seller.delete');
    }
}
