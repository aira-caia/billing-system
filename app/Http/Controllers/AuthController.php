<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\User;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;

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

        $this->log("Login", "User " . $user->username . " logged in");

        //If user is successfully logged in, we will send the token, including the authenticated user info.
        return response()->json($response);
    }
    public function logout(Request $request)
    {
        //Everytime we logout on our app, we are destroying our tokens.
        auth()->user()->tokens()->delete();
        $this->log("Logout", "User " . $request->user()->username . " logged out");
        return response(['message' => "OK"]);
    }

    public function index(Request $request)
    {
        //This method will return the information of authenticated(logged in) user
        $user = $request->user();
        $user['image_path'] = CompanyInfo::find(1)->image_path;
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //We call this method when we update our user
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'nullable|string|confirmed',
            'company_name' => 'required|string',
            'slogan' => 'required|string',
            'image' => 'nullable'
        ]);

        if ($request->file('image')) {
            //Store the image
            $image = $request->file('image');
            $imageName = time() . "." . $image->getClientOriginalExtension();

            $factory = (new Factory)->withServiceAccount(__DIR__ . '/config.json');
            $bucket = $factory->createStorage()->getBucket();
            $path = $bucket->upload(file_get_contents($image), ['name' => 'profile/' . $imageName])->signedUrl(new \DateTime('2400-04-15'));
            $validated['image_path'] = $path;
        }

        if (!$validated['password']) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        CompanyInfo::find(1)->update($validated);

        $user->update($validated);
        return response(['message' => "OK"]);
    }
}
