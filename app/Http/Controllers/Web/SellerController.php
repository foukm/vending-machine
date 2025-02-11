<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        return view('seller.index');
    }

    public function edit(Request $request){
        return view('seller.edit');
    }
}
