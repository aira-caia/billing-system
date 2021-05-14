<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function home()
    {
        $serve = Purchase::sum("count");
        $revenue = number_format(Purchase::sum("amount"), 2);
        $transactions = Payment::count();

        return response()->json(compact('serve', 'revenue', 'transactions'));
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string',],
            'password' => ['required', 'string']
        ]);

        $validated = $validator->validated();
        if ($validator->fails()) {
            return response(['message' => 'Error', 'errors' => $validator->errors()], 400);
        }

        $user = User::where('username', $validated['username'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'These credentials does not match our records.',
                'errors' => []
            ], 400);
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
        if (!$validated['password']) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response(['message' => "OK"]);
    }
}
