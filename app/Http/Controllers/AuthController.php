<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Register;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'username' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('token')->plainTextToken;
        Mail::to($request->email)->send(new Register($user));
        $user->update(['remember_token' => $token]);
        return response()->json([
            "token" => $token,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "username" => $user->username,
            "email" => $user->email,
            'profile_picture' => $user->profile_picture,
            'profile_picture_id' => $user->profile_picture,
        ], 200);
    }
    // Login
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Incorrect credentials"
            ], 401);
        }
        $user->tokens()->where('tokenable_id', $user->id)->delete();
        $token = $user->createToken('web-token')->plainTextToken;
        return response()->json([
            "token" => $token,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "username" => $user->username,
            "email" => $user->email,
            'profile_picture' => $user->profile_picture,
            'profile_picture_id' => $user->profile_picture,
        ], 200);
    }
    // UpdateProfile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        if($request->profile_picture){

            $picture = Cloudinary::upload('data:image/png;base64,'.$request->profile_picture);

            $user = User::update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'profile_picture' => $picture->getSecurePath(),
                'profile_picture_id' => $picture->getPublicId(),
            ]);
        }
        else{
            $user = User::update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
            ]);
        }


        return response()->json([
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "username" => $user->username,
            "email" => $user->email,
            'profile_picture' => $user->profile_picture,
            'profile_picture_id' => $user->profile_picture,
        ], 200);
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'profile_picture' => 'required',
        ]);

        $user = User::update([
            'password' => $request->password,
        ]);

        return response()->json([
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "username" => $user->username,
            "email" => $user->email,
            'profile_picture' => $user->profile_picture,
            'profile_picture_id' => $user->profile_picture,
        ], 200);
    }
    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(null, 204);
    }
}
