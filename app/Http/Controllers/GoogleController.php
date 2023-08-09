<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        $user = Socialite::driver('google')->user();
        $password = env('DEFAULT_PASSWORD');
        $code = rand(100000,999999);

        $exist = User::where('email',$user->email)->first();

        if($exist){
            Auth::login($exist);
            return redirect('/');
        }else{
          $newUser = User::create([
                'email'=>$user->email,
                'password'=>Hash::make($password),
                'name'=>$user->name,
                'avatar'=>$user->avatar,
                'google_id'=>$user->id
            ]);
            Verify::create(['email'=>$newUser->email,'code'=>$code]);
            Auth::login($newUser);

            return redirect('/change-password');
        }

    }
}
