<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('id','!=',$request->user()->id)->select('id','name','email','canManageUser','canManageProduto')->paginate(15);

        return $users;
    }

    public function search(Request $request, String $prompt)
    {
        $users = User::where('name','like','%'.$prompt.'%')->orWhere('email','like','%'.$prompt.'%')->where('id','!=',$request->user()->id)->select('id','name','email','canManageUser','canManageProduto')->paginate(15);

        return $users;
    }

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
        $user->canManageProduto = false;
        $user->canManageUser = false;
        
        $user->save();

        $token_abilities = [];
        if($user->canManageProduto){
            $token_abilities += ['manageProduto'];
        }
        if($user->canManageUser){
            $token_abilities += ['manageUser'];
        }
        $token = $user->createToken($request->token_name, $token_abilities)->plainTextToken;

        return response()->json([
            'token' => $token,
            'canManageProduto' => $user->canManageProduto,
            'canManageUser' => $user->canManageUser,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token_abilities = [];
            if($user->canManageProduto){
                $token_abilities[] = 'manageProduto';
            }
            if($user->canManageUser){
                $token_abilities[] = 'manageUser';
            }
            $token = $user->createToken($request->token_name, $token_abilities)->plainTextToken;

            return response()->json([
                'token' => $token,
                'canManageProduto' => $user->canManageProduto,
                'canManageUser' => $user->canManageUser,
            ], 201);
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

    public function setup()
    {
        if(User::where('email', 'admin@adm')->count() == 0){
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('canManageProduto');
                $table->boolean('canManageUser');
            });

            $user = new User;

            $user->name = 'superAdm';
            $user->email = 'admin@adm';
            $user->password = 'admin';
            $user->canManageProduto = true;
            $user->canManageUser = true;

            $user->save();
            
        }
    }

    public function changeAuthority(Request $request, User $user){
        $changer = $request->user();

        if($changer->tokenCan('manageUser')){

            $user->canManageProduto = $request->canManageProduto;
            $user->canManageUser = $request->canManageUser;
    
            $user->save();
    
            return response()->json($user, 201);
        }
    }
}
