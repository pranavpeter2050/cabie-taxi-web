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
}
