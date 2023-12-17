<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    public function dashboard()
    {
        $user = User::where('user', true)->count();
        $lawyer = User::where('lawyer', true)->count();
        $appointments = rand(10, 20);

        $data = [
            'users' => $user,
            'lawyers' => $lawyer,
            'appointments' => $appointments
        ];

        return response()->json($data, 200);
    }

    public function lawyerCreate(Request $request)
    {

        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
            'region' => $request->input('region'),
            'lawyer' => true,
            'password' => Hash::make($request->input('password'))
        ]);

        return $user;
    }

    public function lawyers()
    {
        $user = User::where('lawyer', true)->get(['id', 'name', 'phone_number', 'address', 'region', 'username', 'email']);
        return $user;
    }

    public function users()
    {
        $user = User::where('user', true)->get(['id', 'name', 'phone_number', 'address', 'region', 'username', 'email']);
        return $user;
    }

    public function userAuth()
    {
        $user = Auth::user();
        return $user;
    }

    public function info($id)
    {
        $user = User::find($id, ['id', 'name', 'phone_number', 'address', 'region', 'username', 'email']);
        return $user;
    }

    public function update($id, Request $request)
    {
        try {

            $user = User::find($id);
            $user->update([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'region' => $request->input('region'),
            ]);

            if (!is_null($request->input('password'))) {
                if (!Hash::check($request->input('password'), $user->password)) {
                    $user->password = Hash::make($request->input('password'));
                    $user->save();
                } else {
                    return response()->json(['error' => 'Old Password and new password cannot be same'], 403);
                }
            }
            return response()->json(['success' => 'Updated']);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Something Went Wrong'], 403);
        }
    }

    public function delete($id)
    {

        try {
            $user = User::find($id);
            $user->delete();
            return response()->json(['success' => 'successfully deleted']);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Something went wrong'], 0);
        }
    }
}
