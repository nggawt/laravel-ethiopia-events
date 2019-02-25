<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getLogin', 'getUserLogged', 'store', 'destroy']]);
       
    }

    public function getUserLogged()
    {

        if($status = Auth::check()){
            return response()->json(['status' => $status, 'user' => $this->getUser()],200);
        } 
        return response()->json(['status' => $status],200);
        // return isset($autUser)? response()->json(['status' => true, 'user' => $user],200):
        // return $this->respondWithToken($token);
    }
    
    public function getLogin(Request $req){
        $this->validate($req,[
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        //return "jjkkkjk";
        
        $credentials = request(['email', 'password']);
        //return $request->all();
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    
    public function getLogout(){
        Auth::logout();
        return redirect()->route('home');
    }

    public function store(Request $req){
        
        $this->validate($req,[

            'name' => 'required|string|min:3',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'passwordConfirm' => 'required|string|same:password',
            'city' => 'min:3|string',
            'area' => 'required|string|min:3',
            'about' => 'min:12|string',
            'tel' => 'digits_between:8,10',
        ]);
        $credentials = request(['email', 'password']);
        // $req['password'] =  bcrypt($req['password']);
        $req = $req->except(['passwordConfirm']);
        User::create($req);
        sleep(1);
        //$this->getLogin($credentials);

        // $credentials = request(['email', 'password']);
        
        // //return $request->all();
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($this->respondWithToken($token),200);

    }

    public function destroy(Request $request, $id){

        $authUser = auth()->user();
        $user = User::findOrfail($id);

        /***** validate user and delete*****/
        if($authUser->id == $id && $authUser->email == $request['email']){
            // $user->delete();
            return response()->json(["message" => "user was deleted!"],200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
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

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tel' => $user->tel,
            'about' => $user->about,
            'area' => $user->area,
            'city' => $user->city,
            'customer' => $customer? $customer->only(['company', 'businessType', 'title', 'contact', 'discription']): false
        ];
    }
 
}
