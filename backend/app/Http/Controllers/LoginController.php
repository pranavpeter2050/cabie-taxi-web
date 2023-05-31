<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) {
        // validate the phone number
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        // find or create a User model associated with the phone number
        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if(!$user) {
            return response()->json(['message' => 'Could not process a User with that phone number.'], 401);
        }

        // send the user an OTP
        $user->notify(new LoginNeedsVerification());

        // return back a response to frontend
        return response()->json(['message' => 'OTP notification sent successfullly.']);
    }

    public function verify(Request $request) {
        // validate the incoming request
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        // find the user
        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
            ->first();

        // is the login OTP received, the same as the one saved in DB
        // if so, return back a auth token
        if($user) {
            $user->update([
                'login_code' => null
            ]);

            return $user->createToken($request->login_code)->plainTextToken;
        }

        // if not, return back a message
        return response()->json(['message' => 'Invalid verification code.'], 401);
    }
}
