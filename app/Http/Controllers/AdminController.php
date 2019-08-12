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
            'authority' => 'required|numeric|between:1,3'
            // 'authority' => 'string|min:3|max:30'
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

    public function store(Request $request){
        
        $this->validate($request, $this->admin_ruls);

        $credentials = $request->only(['email', 'password']);
        $request['password'] =  bcrypt($request['password']);
        $createItems = $request->except(['passwordConfirm', 'authority']);

        $admin = Admin::create($createItems);
        $admin->roles()->attach($request['authority']);

        return response()->json($admin,200);
    }

    public function update(Request $request, Admin $admin){

        $requestItems = $request->except(['email', 'password']);

        $items = collect($requestItems)->intersectByKeys($this->admin_ruls);
        $ruls = collect($this->admin_ruls)->intersectByKeys($items)->toArray();
        $isValid = $this->isValid($items->toArray(), $ruls, 'update');

        /* send message fail if invalid */
        if(! $isValid) return response()->json(['status' => false ,$this->getMessages()], 200);

        /* update admin and admin role and send message success */
        $nonAutority = $items->except('authority')->toArray();
        if(isset($request['authority'])){

            $admin->roles()->sync($request['authority']);
            if(! empty($nonAutority)) $admin->update($nonAutority);
        }else{
             $admin->update($nonAutority);
        }
        return response()->json(['status' => true, 'admin' => $admin ,'messages' => $this->getMessages(), 'items' => $items], 200);
    }

    public function authAdmin(){

    	$admin = Auth::guard('admin');

    	if(! $status = $admin->check()){
    		return response()->json(['status' => $status], 200);
        } 
        return response()->json( $admin->user()->respondWithToken(request('token')),200);
    }

    public function logout()
    {
        $logOut = auth('admin')->logout();
        return response()->json(['message' =>  'Successfully logged out', 'logout' => $logOut], 200);
    }

    public function destroy(Request $request, Admin $admin){

        return response()->json(['message' =>  'Successfully admin deleted!', 'admin' => $admin], 200);
    }

    private function isValid(array $inputs = [], array $rules = [], $item){
        $rules = count($rules)? $rules: $this->itemsRule;
        $validator = \Validator::make($inputs, $rules);

        $validator->fails()? $this->setErrorsMessages($validator): $this->setSuccessMessages([$item => $item]);
        //return true;
        return $validator->fails()? false:true;
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