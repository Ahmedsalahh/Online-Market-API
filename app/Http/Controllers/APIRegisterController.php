<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;
class APIRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user  = User::first();
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
        
    }
    public function getAllUsers()
    {
        return response()->json(User::all());
    }

    public function getAllUsersbyAdmin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $user = User::where('email',$request->email)->first();
        if($user != null && $user->isAdmin())
            return response()->json(User::all());
        return response()->json('Unauthorized',401);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email','password');
        $token = JWTAuth::attempt($credentials);
        try {
            if(!$token){
                return response()->json(['error'=>'invalid email or password'],401);
            }
        } catch (JWTException $e) {
            return response()->json(['error'=>'could not create token'],500);
        }
        return response()->json(compact('token'));
    }
}
