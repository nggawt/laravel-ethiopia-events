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
        $this->middleware('auth:api', ['except' => ['getLogin', 'store']]);
       
    }
    
    public function getUsers(){
        $users = User::all();
        $articles = Articles::all();
        return view('welcome',['users' => $users,'articles' => $articles]);
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

            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'passwordConfirm' => 'required|same:password',
            'city' => 'min:3',
            'area' => 'required|min:3',
            'about' => 'min:6',
            'tel' => 'digits_between:9,10',
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

        return $this->respondWithToken($token);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
 
}
