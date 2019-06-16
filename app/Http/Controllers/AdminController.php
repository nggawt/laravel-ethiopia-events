<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\Role;
use App\Repo\traits\Messages;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminController extends Controller
{

	use Messages;
    private $admin_ruls = [

            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
            'passwordConfirm' => 'required|string|same:password|max:255',
            'admin_type' => 'string|min:3|max:30'
        ];

	function __construct()
    {
        $this->middleware('auth:admin');// , ['except' => 'authAdmin' , 'index']
       
    }

    public function index(){
        // return Admin::all();
        if($status = Auth::guard('admin')->check()){
            return response()->json($this->getAuthoritedAdmin(Admin::all()),200);
        } 
        return response()->json(['status' => $status],200);
    }

    protected function getAuthoritedAdmin($admins){
    	foreach ($admins as $admin) {
    		$admin['authority'] = $admin->getAdminWithAuthority();
    	}
    	return $admins;
    }

    public function store(Request $req){
        
        $this->validate($req, $this->admin_ruls);

        $credentials = request(['email', 'password']);
        $req['password'] =  bcrypt($req['password']);
        $createItems = $req->except(['passwordConfirm', 'admin_type']);

        $idRole = Role::where('name', $req['admin_type'])->first()->id;

        $admin = Admin::create($createItems);
        $admin->roles()->attach($idRole);

        $credentials = request(['email', 'password']);
        
        if (! $token = Auth::guard('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($this->respondWithToken($token),200);
    }

    public function authAdmin(){

    	$admin = Auth::guard('admin');

    	if(! $status = $admin->check()){
    		return response()->json(['status' => $status], 200);
        } 
        return response()->json( $admin->user()->respondWithToken(request('token')),200);
    }
}

/*

 public function getAuthenticatedUser()
{
        try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                        return response()->json(['user_not_found'], 404);
                }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
}


*/