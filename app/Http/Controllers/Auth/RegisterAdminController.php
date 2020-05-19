<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Auth;
use App\Repo\traits\Messages;
use App\Admin;
// use App\Role;

class RegisterAdminController extends Controller
{
    use Messages;

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

    function __construct()
    {
        $this->middleware('guest:admin');
       
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
            'authority' => 'numeric|between:1,3'
            // 'authority' => 'string|min:3|max:30'
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
        $credentials = $useData->only(['email', 'password'])->toArray();

        $useData['password'] = Hash::make($useData['password']);
        $createItems = collect($useData)->except('passwordConfirm', 'authority')->toArray();


        $admin = Admin::create($createItems);
        $admin->roles()->attach($data['authority']);
        
        return $admin;
    }


    protected function registered(Request $request, $admin)
    {
        
        if (! $token = auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($admin->respondWithToken($token),200);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }


}
