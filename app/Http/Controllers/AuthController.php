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

    public function index(Request $request)
    {
        return $request->user();
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'nullable|string|confirmed'
        ]);
        if(!$validated['password']) {
            unset($validated['password']);
        }else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response(['message' => "OK"]);
    }
}
