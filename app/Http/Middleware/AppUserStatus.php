<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AppUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->status == 0) {
            return response()->json(['status' => 0, 'msg' => config('constants.blockMsg'), 'payload' => (object) []], config('constants.ok'));
        }
        
        return $next($request);
    }
}
