<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Closure;

class Admin
{

  /**
     * Check user role for adding product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    public function handle($request, Closure $next)
    {
        // print_r(Auth::user());
        if (Auth::check() && (Auth::user()->role_id != '1' && Auth::user()->role_id != '3')) {
            return response()->json([
                'error'=>'Permission Denied',
                'message' => 'Permission Denied'
            ], 401);
        }

            return $next($request);
    }

}
