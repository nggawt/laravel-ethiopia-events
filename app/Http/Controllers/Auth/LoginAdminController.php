<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginAdminController extends Controller
{

    //protected $maxLoginAttempts = 2; // Amount of bad attempts user can make
    //protected $lockoutTime = 300; // Time for which user is going to be blocked in seconds
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/app';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api')->except('logout');
        $this->middleware('auth:admin', ['except' => 'login']);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized admin user, msg from auth\\LoginadminController!.'], 401);
        }
        return response()->json(auth('admin')->user()->respondWithToken(auth('admin')->refresh()),200);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->guard('admin')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('admin')->logout();

        return response()->json(['message' => 'Successfully logged out, msg from auth\\LoginadminController!.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json(auth('admin')->user()->getAdminWithAuthority(auth()->guard('admin')->refresh()), 200);
    }

}
