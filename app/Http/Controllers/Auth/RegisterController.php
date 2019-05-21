<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
            'passwordConfirm' => 'required|string|same:password|max:255',
            'city' => 'string|min:3|max:30',
            'area' => 'required|string|min:3|max:30',
            'about' => 'string|min:12',
            'tel' => 'digits_between:8,10',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $useData = collect($data);
        $credentials = $useData->only(['email', 'password']);

        $useData['password'] = Hash::make($useData['password']);
        $user = collect($useData)->except('passwordConfirm')->toArray();

        User::create($user);
        sleep(1);
        // User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'city' => $data['city'],
        //     'area' => $data['area'],
        //     'about' => $data['about'],
        //     'tel' => $data['tel'],
        //     'password' => Hash::make($data['password']),
        // ]);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($this->respondWithToken($token),200);
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
        $messages = $user->messages;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tel' => $user->tel,
            'about' => $user->about,
            'area' => $user->area,
            'city' => $user->city,
            'messages' => $messages? $messages: false,
            'customer' => $customer? $customer->only(['company', 'businessType', 'title', 'contact', 'discription']): false,
            'events' => $events? $events: false
        ];
    }
}
