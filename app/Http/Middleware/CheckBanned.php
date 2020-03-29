<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Forbidden_user;

class CheckBanned
{
    public function handle($request, Closure $next)

    {

        # find user by: emails, domians/ip, token..., reject request whith message

        $user = $this->checkForbiddenUser($request);
        // return response()->json(['forbidden_user' => $user], 200);
        if ($user && $user->banned_until && now()->lessThan($user->banned_until)) {

            $banned_days = now()->diffInDays($user->banned_until);
            auth('api')->check()? auth('api')->logout(): '';

            if ($banned_days > 14) {
                $message = 'Your account has been suspended. Please contact administrator.';
            } else {
                $message = 'Your account has been suspended for '.$banned_days.' '.str_plural('day', $banned_days).'. Please contact administrator.';
            }
            return response()->json(['message' => $message, 'forbidden_user' => $user], 200);
        }
        return $next($request);
    }

    protected function checkForbiddenUser($request){
        // check from signed user
        $userCheck = auth('api')->check();
        $user = $userCheck? auth('api')->user(): User::where('email', $request['email'])->first();
        $user = (! empty($user) && $user->email)? $user->forbidden: false; 

        // if signed user not forbidden check public user by email, ip, domian...
        $forbiddenUser = $user? $user:Forbidden_user::where('email', $request['email'])->first();
        return $forbiddenUser;
    }
}
