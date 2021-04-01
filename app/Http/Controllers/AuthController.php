<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required','string','exists:users'],
            'password' => ['required', 'string']
        ]);

        $user = User::where('username',$validated['username'])->first();
        if(!Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message' =>'These credentials does not match our records.',
                'errors' => []
            ],400);
        }
        $token = $user->createToken('appToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];


        return response()->json($response);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(['message' => "OK"]);
    }
}
