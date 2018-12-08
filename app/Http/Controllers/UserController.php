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
        //$credentials = request(['email', 'password']);
        //return $request->all();
        $user = [];
        if(Auth::check()){
            $autUser = Auth::user();

            $user = [
                'name' => $autUser->name,
                'id' => $autUser->id,
                'email' => $autUser->email
            ];
        } 
        
        return isset($autUser)? response()->json(['status' => true, 'user' => $user],200):response()->json(['status' => Auth::check()],200);
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
        $user = auth()->user();

        return [
            'user' => [
                'name' => $user->name,
                'id' => $user->id,
                'email' => $user->email
            ],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
 
}
