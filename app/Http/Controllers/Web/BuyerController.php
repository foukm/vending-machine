<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index()
    {
        return view('buyer.index');
    }

    public function edit(Request $request){
        return view('buyer.edit');
    }
}
