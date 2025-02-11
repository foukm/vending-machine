<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show(Request $request, Product $product){
        $user = auth()->user();
        if ($user->role !== 'seller' || $product->sellerId !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Only product seller can view product'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'seller') {
            return response()->json([
                'success' => false,
                'error' => 'Only seller can add product'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'productName' => 'required|string',
            'amountAvailable' => 'required|integer',
            'cost' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = new Product();
        if($product->saveProduct($request->all(), $user)){
            return response()->json([
                'success' => true,
                'message' => 'Product added successfully',
                'product' => [
                    'productName' => $product->productName,
                    'amountAvailable' => $product->amountAvailable,
                    'cost' => $product->cost
                ]
            ]);
        }else{
            return response()->json([
                'success' => false,
                'error' => 'Failed to add product'
            ], 422);
        }
    }

    public function update(Request $request, Product $product){
        $user = auth()->user();
        if ($user->role !== 'seller' || $user->id !== $product->sellerId) {
            return response()->json([
                'success' => false,
                'error' => 'Only seller can edit product'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'amountAvailable' => 'required|integer',
            'cost' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if($product->updateProduct($request->all())){
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => [
                    'productName' => $product->productName,
                    'amountAvailable' => $product->amountAvailable,
                    'cost' => $product->cost
                ]
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product'
            ], 401);
        }
    }

    public function destroy(Request $request,Product $product){
        $user = auth()->user();
        if ($user->role !== 'seller' || $user->id !== $product->sellerId) {
            return response()->json([
                'success' => false,
                'error' => 'Only seller can delete product'
            ], 403);
        }

        return response()->json([
            'success' => $product->delete(),
            'message' => 'Product deleted successfully'
        ]);
    }
}
