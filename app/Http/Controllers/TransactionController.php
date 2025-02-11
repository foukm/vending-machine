<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function deposit(Request $request)
    {

        $user = auth()->user();
        if ($user->role !== 'buyer') {
            return response()->json([
                'success' => false,
                'error' => 'Only buyers can deposit'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|in:5,10,20,50,100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Amount should be 5, 10, 20, 50 or 100'
            ], 422);
        }

        if ($user->deposit($request->amount)) {
            return response()->json([
                'success' => true,
                'deposit' => $user->deposit
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Failed to deposit'
            ]);
        }
    }

    public function reset()
    {
        $user = auth()->user();
        if ($user->role !== 'buyer') {
            return response()->json([
                'success' => false,
                'error' => 'Only buyers can reset deposit'
            ], 403);
        }

        if ($user->resetDeposit()) {
            return response()->json([
                'success' => true,
                'message' => 'Deposit reset to 0'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Failed to reset deposit '
            ]);
        }
    }

    public function buy(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'buyer'){
            return response()->json([
                'error' => 'Only buyers can buy'
            ], 403);
        }

        $request->validate([
            'productId' => 'required|exists:products,id',
            'amount' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->productId);
        $totalCost = $product->cost * $request->amount;

        if ($user->deposit < $totalCost){
            return response()->json(['error' => 'Insufficient funds'], 400);
        }
        if ($product->amountAvailable < $request->amount){
            return response()->json(['error' => 'Not enough stock'], 400);
        }

        $product->amount($request->amount);
        $user->buy($totalCost);

        return response()->json([
            'success' => true,
            'message' => "Bought successfully",
            'spent' => $totalCost,
            'deposit' => $user->deposit,
            'purchased' => $request->amount . ' x ' . $product->productName,
            'change' => $this->calculateChange($user->deposit),
        ]);
    }

    private function calculateChange($remaining)
    {
        $coins = [100, 50, 20, 10, 5];
        $change = [];

        foreach ($coins as $coin) {
            while ($remaining >= $coin) {
                $change[] = $coin;
                $remaining -= $coin;
            }
        }

        if(count($change) == 0)
            $change[] = 0;

        return $change;
    }
}
