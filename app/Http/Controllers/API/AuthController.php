<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;

class AuthController
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "role_id" => "required",
            "password" => "required",
            // "confirm_password" => "required|same:password",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => "validator error",
                "data" => $validator->errors()->all()
            ]);
        };

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => $request->role_id,
            "password" => bcrypt($request->password)
        ]);
            
        $response = [];
        $response["token"] = $user->createToken("MyApp")->plainTextToken;
        $response["name"] = $user->name;
        $response["email"] = $user->email;

        return response()->json([
            "status" => 1,
            "message" => "User Registered",
            "data" => $response
        ]);
    }

    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $response = [];
            $response["token"] = $user->createToken("MyApp")->plainTextToken;
            $response["name"] = $user->name;
            $response["email"] = $user->email;
            $response["role_id"] = $user->role_id;

            return response()->json([
                "status" => 1,
                "message" => "User Registered",
                "data" => $response
            ]);
        }

        return response()->json([
            "status" => 0,
            "message" => "Authentication Failed",
            "data" => null
        ]);
    }
}
