<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success'=>false,
                'error' => 'Invalid credentials'], 401);
        }

        if ($user->tokens()->count() > 0) {
            return response()->json([
                'error' => 'There is already an active session using your account.',
            ], 409);
        }

        return response()->json([
            'username' => $user->username,
            'role'=> $user->role,
            'deposit' => $user->deposit,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $validator = $this->validateRegister($data);
        if(!$validator->fails()){
            $user = new User();
            if($user->saveUser($data)){
                return response()->json([
                    'success' => true,
                    'message' => 'User '.$user->username.' created successfully'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'error' => 'save_user_error',
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

    private function validateRegister($data)
    {
        return Validator::make($data, [
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:buyer,seller',
        ]);
    }
}
