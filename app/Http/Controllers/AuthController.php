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
        // This is to display the data on home components of admin (Web)
        $serve = Purchase::sum("count");
        $revenue = number_format(Purchase::sum("amount"), 2);
        $transactions = Payment::count();

        return response()->json(compact('serve', 'revenue', 'transactions'));
    }

    public function login(Request $request)
    {
        // This function is called whenever we make an attempt to login on the application
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

        //Everytime we login successfully,we are generating a token, this token
        //is then used both by mobile and web app to request further data to our database(backend).

        //if a user does not have a token, he will be classified as unauthorized user, because he doesnt
        //carry a token, and will not be able to request from our API.
        $token = $user->createToken('appToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        //If user is successfully logged in, we will send the token, including the authenticated user info.
        return response()->json($response);
    }
    public function logout()
    {
        //Everytime we logout on our app, we are destroying our tokens.
        auth()->user()->tokens()->delete();
        return response(['message' => "OK"]);
    }

    public function index(Request $request)
    {
        //This method will return the information of authenticated(logged in) user
        return $request->user();
    }

    public function update(Request $request, User $user)
    {
        //We call this method when we update our user
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
