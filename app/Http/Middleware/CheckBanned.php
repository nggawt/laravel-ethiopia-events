<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckBanned
{
    public function handle($request, Closure $next)

    {

        /*if (auth('api')->check() && auth('api')->user()->banned_until && now()->lessThan(auth('api')->user()->banned_until)) {
            $banned_days = now()->diffInDays(auth('api')->user()->banned_until);
            auth('api')->logout();

            if ($banned_days > 14) {
                $message = 'Your account has been suspended. Please contact administrator.';
            } else {
                $message = 'Your account has been suspended for '.$banned_days.' '.str_plural('day', $banned_days).'. Please contact administrator.';
            }

            return response()->json(['message' => $message], 200);
        }*/
        $userCheck = auth('api')->check();
        $user = $userCheck? auth('api')->user(): User::where('email', $request['email'])->first();
        //$user = $user? $user: isset($request['email'])? User::where('email', $request['email'])->first(): false;
        if ($user && $user->banned_until && now()->lessThan($user->banned_until)) {
            $banned_days = now()->diffInDays($user->banned_until);
            // $userCheck? auth('api')->logout(): '';

            if ($banned_days > 14) {
                $message = 'Your account has been suspended. Please contact administrator.';
            } else {
                $message = 'Your account has been suspended for '.$banned_days.' '.str_plural('day', $banned_days).'. Please contact administrator.';
            }

            return response()->json(['message' => $message], 200);
            // return redirect()->route('login')->withMessage($message);
        }
        return $next($request);
    }
}
