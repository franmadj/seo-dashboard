<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Flugg\Responder\Facades\Responder;
use Watson\Validating\ValidationException;
use App\Setting;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('dashboard');
    }

    public function changePassword(Request $request) {

        if (!(Hash::check($request->get('currentPassword'), Auth::user()->password))) {
            return Responder::error("Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('currentPassword'), $request->get('newPassword')) == 0) {
            //Current password and new password are same
            return Responder::error("New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('newPassword'));
        $user->save();
        return Responder::success();
    }

    

}
