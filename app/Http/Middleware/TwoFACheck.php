<?php

namespace App\Http\Middleware;

use App\Models\Verify;
use Closure;
use Illuminate\Http\Request;

class TwoFACheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user=auth()->user();
        $verify = Verify::where('email',$user->email)->first();
        if($user->enable_2fa && $verify->is_active == 1){
            return redirect('/2fa-verification');
        }

        return $next($request);
    }
}
