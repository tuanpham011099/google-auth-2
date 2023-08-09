<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verify;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class UserController extends Controller
{

    protected function connect(){
        $sid = env('TWILIO_SID');
        $secret = env('TWILIO_TOKEN');

        return new Client($sid, $secret);
    }

    public function addPasswordView(){
        return view('change-password');
    }

    public function login(Request $request){
        $validate = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        if(!$validate){
            return redirect('/login')->with(['error'=>'Wrong email or password!!!']);
        }

        $user = User::where('email',$request->email)->first();


        if($user->enable_2fa){
            $rand = rand(100000, 999999);
            Verify::where('email', $user->email)->update(['code' => $rand]);
        }

        if(!$user){
            return redirect('/login')->with(['error' => 'No user found!!!']);
        }
        $password_check = Hash::check($request->password,$user->password);

        if(!$password_check){
            return redirect('/login')->with(['error' => 'Wrong email or password!!!']);
        }

        Auth::login($user);

        return redirect('/');

    }

    public function logout(){
        $user=auth()->user();

        if($user->enable_2fa){
            Verify::where(['email'=>$user->email])->update(['is_active'=>1]);
        }

        Auth::logout();

        return redirect('/login');
    }

    public function addPassword(Request $request){
        $user = auth()->user();
        $old_password = $request->old_password;
        $new_password = $request->password;
        $password_confirm = $request->password_confirm;

        if($password_confirm !== $new_password){
            return redirect('/change-password')->with(['error'=>'Passwords must match']);
        }

        if ($user->is_password_changed === 0) {
            User::where('email',$user->email)->update(['password'=>Hash::make($new_password),'is_password_changed'=>true]);

            return redirect('/change-password')->with(['success'=>'Password change successfully']);
        }

        $pass_check = Hash::check($old_password, $user->password);

        if(!$pass_check){
            return redirect('/change-password')->with(['error' => 'Wrong password!!!']);
        }

        User::where('email', $user->email)->update(['password' => Hash::make($new_password)]);

        return redirect('/change-password')->with(['Success' => 'Password change successfuly']);

    }

    public function profile(){
        return view('profile');
    }

    public function phone(){
        return view('add-phone');
    }

    public function addPhone(Request $request){
        $user = auth()->user();
        $code = rand(100000,999999);

        $exist = User::where('phone',$request->phone)->first();

        if($exist){
            return redirect('/add-phone')->with(['error' => 'Phone number already in use']);
        }

        User::where('email',$user->email)->update(['phone'=>$request->phone,'verification_code'=>$code]);

        try {
            $this->sendMsg($request->phone,$code);

            return redirect('/add-phone')->with(['success'=>'We"ve sent a verification code to your number']);
        } catch (Exception $th) {
            return redirect('/add-phone')->with(['error' => $th->getMessage()]);
        }
    }

    public function verifyPhone(Request $request){
        $user = auth()->user();
        $code = $request->code;

        $exist = User::where('email',$user->email)->first();

        if($exist->verification_code == $code){
            $exist->update(['verification_code'=>null,'phone_verified'=>1]);

            return redirect('/add-phone')->with(['success' => 'Phone number verified success']);
        }else{
            return redirect('/add-phone')->with(['error' => 'Wrong code']);
        }
    }

    protected function sendMsg($receiver,$code){
        try {
            $from = env('TWILIO_FROM');
            $twilio = $this->connect();

            $twilio->messages->create($receiver,[
                'body'=>'Your verification code: '.$code,
                'from'=>$from
            ]);
        } catch (Exception $th) {
            throw $th;
        }
    }

    public function enable2FAView(){
        $user = auth()->user();
        $code = rand(100000, 999999);

        try {
            User::where('email', $user->email)->update(['verification_code' => $code]);
           $this->sendMsg($user->phone,$code);

           return view('2fa-enable');
        } catch (Exception $th) {
            return view('2fa-enable')->with(['error'=>$th->getMessage()]);
        }
    }

    public function enable2FA(Request $request){
        $code = $request->code;
        $user = auth()->user();

        $exist = User::where('email',$user->email)->first();

        if($code == $exist->verification_code){
            $exist->update(['enable_2fa'=>true,'verification_code'=>null]);
            Verify::where('email',$user->email)->update(['is_active'=>0]);
        }

        return redirect('/profile')->with(['success'=>'You\'ve enabled 2FA sms']);
    }

    public function TwoFAVerificationView(){
        $user = auth()->user();

        $exist = Verify::where(['email'=>$user->email])->first();
        if($exist->code){
            $this->sendMsg($user->phone, $exist->code);
        }else{
            $code = rand(100000, 999999);
            Verify::updateOrCreate(['email' => $user->email],['code'=>$code]);
            $this->sendMsg($user->phone, $code);
        }
        return view('2fa-verification');
    }

    public function TwoFAVerification(Request $request){
        $user = auth()->user();
        $exist = Verify::where('email',$user->email)->first();

        if($request->code == $exist->code){
            $exist->update(['code'=>null,'is_active'=>0]);

            return redirect('profile')->with(['success'=>'Logged in']);
        }else{
            return redirect('2fa-verification')->with(['error' => 'Wrong code']);
        }
    }

    public function disable2FAView(){
        return view('2fa-disable');
    }

    public function disable2FA(Request $request){
        $password = $request->password;
        $user = auth()->user();

        $userFind = User::where('email',$user->email)->first();

        $password_check = Hash::check($password, $userFind->password);

        $userFind->update(['enable_2fa'=>0]);

        if(!$password_check){
            return redirect('profile')->with(['error' => 'Wrong password']);
        }
        return  redirect('2fa-disable')->with(['success' => '2FA disabled']);
    }
}
