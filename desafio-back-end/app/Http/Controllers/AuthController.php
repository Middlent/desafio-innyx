<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $user = new User;
        
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'max:50', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        
        $user->save();

        $token = $user->createToken($request->token_name);

        return response()->json($token, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken($request->token_name);

            return response()->json($token, 201);
        }
 
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401 );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'logout' => 'Session finished successfully'
        ], 200);
    }
}
