<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // public function reset(Request $request){
        
    // }

    protected function sendResetResponse($response)
    {
        return $this->respondWithToken(auth('api')->refresh());
        //return response()->json(["message" => "your password was reseted", "res" => $response, "auth" => auth()->user()], 200);

        //return redirect($this->redirectPath())
                            //->with('status', trans($response));
    }

    protected function respondWithToken($token)
    {
        
        return [
            'user' => $this->getUser(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    private function getUser(){

        $user = auth()->user();
        $customer = $user->customer;
        $events = $user->events;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tel' => $user->tel,
            'about' => $user->about,
            'area' => $user->area,
            'city' => $user->city,
            'customer' => $customer? $customer->only(['company', 'businessType', 'title', 'contact', 'discription']): false,
            'events' => $events? $events: false
        ];
    }
}
