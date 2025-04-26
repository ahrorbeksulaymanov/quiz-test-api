<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 0,
                'message' => 'Email yoki parol noto‘g‘ri!'
            ], 401);
        }

        $user = User::where("email", $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 1,
            "message"=> "Login muvaffaqqiyatli bo'ldi",
            "access_token"=> $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|boolean',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Siz muvaffaqiyatli ro‘yxatdan o‘tdingiz!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Chiqildi (logout)'
        ]);
        
    }

    public function me(Request $request)
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'successfull!',
            'access_token' => $newToken,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }
}
