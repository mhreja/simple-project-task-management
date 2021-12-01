<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Hash;

class FirstLoginController extends Controller
{    
    public function index(Request $request){
        if(!Auth::user()){
            abort(404);
        }

        if(Auth::user()->first_login != NULL){
            return redirect()->route('dashboard');
        }else{
            return view('auth.first-login-pass-change');
        }
    }


    public function resetPassword(Request $request){
        if(!Auth::user()){
            abort(404);
        }

        $request->validate([
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
            'first_login' => now()->format('Y-m-d h:i:s')
        ]);

        Auth::logout();

        return redirect()->route('login')->with('status', 'Pasword reset successful! Please Login again.');
    }
}
