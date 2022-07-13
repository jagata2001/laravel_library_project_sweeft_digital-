<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
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
        if(Auth::check()){
          //0 == normal user, 1 == admin
          if(Auth::user()->role == 1){
              return $next($request);
          }else{
            return redirect()->route('showBooks')->with('message','Access Denied, You are not admin!');
          }
        }else{
            return redirect()->route('login')->with('message','Access Denied, You are logged in!');
        }

    }
}
