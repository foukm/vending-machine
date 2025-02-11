<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function products(Request $request){
        $user = auth()->user();
        if ($user->role !== 'seller') {
            return response()->json([
                'success' => false,
                'error' => 'Only sellers can see their products'
            ], 403);
        }

        $products = Product::query()->where('sellerId','=',$user->id)->get();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show(Request $request){
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function update(Request $request){
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'required|min:6',
        ]);

        if(!$validator->fails()){
            if($user->updateUser($request->all())){
                $request->user()->tokens()->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User '.$user->username.' updated successfully. You\'ll be logged out from the system'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'error' => 'update_user_error',
                    'errors' => $validator->errors()
                ], 422);
            }
        }else{
            return response()->json([
                'success' => false,
                'error' => 'validate_register_data_error',
                'errors' => $validator->errors()
            ],422);
        }
    }

    public function destroy(Request $request){

        $user = auth()->user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User has been deleted.'
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke all tokens for the user
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User has been logged out from all active sessions.'
        ]);
    }

    public function logoutAll(Request $request)
    {
        // Revoke all tokens for the user

        if(!$request->has('username')){
            return response()->json([
                'success' => false,
                'message' => 'Username is missing.'
            ], 422);
        }
        $user = User::query()->where('username','=',$request->input('username'))->first();

        if(is_null($user)){
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 422);
        }

        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User has been logged out from all active sessions.'
        ]);
    }
}
