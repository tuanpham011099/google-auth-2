<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService{


    const VERIFICATION='verification';
    const TWOFA='2facode';
    const TWOFACHECK='isActive';

    public function setVerification($key,$verification_code){
        return Cache::set($key. self::VERIFICATION, $verification_code);
    }

    public function getVerification($key){
        return Cache::pull($key . self::VERIFICATION);
    }

    public function set2FACode($key,$verification_code){
        return Cache::set($key . self::TWOFA, $verification_code);
    }

    public function get2FACode($key)
    {
        return Cache::pull($key . self::TWOFA);
    }

    public function is2FAActive($key,$method='set',$data=null){
        if($method=='set'){
           return Cache::set($key.self::TWOFACHECK,$data);
        }else{
            return Cache::get($key.self::TWOFACHECK);
        }
    }

    public function forget($key){
        return Cache::forget($key);
    }
}
