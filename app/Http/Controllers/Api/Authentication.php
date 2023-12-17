<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Authentication extends Controller
{

    public function signup(Request $request)
    {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'region' => $request->region,
                'password' => bcrypt($request->password),
            ]);

            return $user;
    }
    public function login(Request $request){

        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $username = Auth::user()->username;
            $user = User::where('username', $username)->first();

            if($user->lawyer)
            {
                $link = "/Lawyer";
            }
            if($user->admin)
            {
                $link = "/Admin/Dashboard";
            }
            if($user->user)
            {
                $link = "/Home/Chats";
            }

            $response = [
                'success' => $user,
                'token' => $user->createToken($username)->plainTextToken,
                'access' => $link
            ];

            return $response;
        }

        if (!User::where('email', $credentials['email'])->first()) {
            throw ValidationException::withMessages([
                'Username' => ['The provided email is incorrect or not yet registered.'],
            ]);
        } else {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();
        if (! $request) {
            throw ValidationException::withMessages([
                'token' => ['logout unsuccessful'],
            ]);
        }
        $response = [
            'message' => 'logout successfully.'
        ];
        return $response;
    }
}
