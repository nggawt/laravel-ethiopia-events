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
            'email' => 'required|string|email|unique:admins,email|max:255',
            'password' => 'required|string|min:6|max:255',
            'passwordConfirm' => 'required|string|same:password|max:255',
            'authority' => 'required|numeric|between:1,3'
            // 'authority' => 'string|min:3|max:30'
        ];

	function __construct()
    {
        // $this->middleware('auth:admin');// , ['except' => 'authAdmin' , 'index']
       
    }

    public function index(){
        // return Admin::all();
        if($status = Auth::guard('admin')->check()){
            return response()->json($this->getAuthoritedAdmin(Admin::all()),200);
        } 
        return response()->json(['status' => $status],200);
    }

    protected function getAuthoritedAdmin($admins){
        $adminsProps = [];
    	foreach ($admins as $admin) {

            $authority = $admin->roles()->first()->only(['id', 'name', 'slug', 'permissions']);
            array_push($adminsProps, [
                'user' => $admin,
                'authority' => $authority ,
                'roles' => $admin->roles
            ]);
            //if(config("app.adminstrator.email") == $admin->email) $admin['roles'] = Role::all();
    	}
    	return $adminsProps;
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
        return response()->json([
            'status' => true, 
            'admin' => $admin,
            'messages' => $this->getMessages(), 
            'items' => $items
        ], 200);
    }

    public function authAdmin(){

    	$admin = Auth::guard('admin');
    	if(! $status = $admin->check()){
    		return response()->json([
                'message' =>  'You are not login!',
                'status' => $status
            ], 200);
        }
        
        $admintrator = $admin->user()->respondWithToken(request('token'))->except(['access_token', 'token_type', 'expires_in', 'status']);
        if($admin  && in_array($admin->user()->email, config("app.adminstrator.email"))){
            $admintrator['roles'] =  Role::all();
        }
        return response()->json($admintrator, 200);
    }

    public function logout()
    {
        auth('admin')->logout();
        return response()->json([
            'message' =>  'Successfully logged out', 
            'status' => true
            ], 200);
    }

    public function destroy(Request $request, Admin $admin){
        // $admin->delete();
        return response()->json([
            'message' =>  'Successfully admin deleted!', 
            'status' => true
        ], 200);
    }

    public function changePassword(Request $request, Admin $admin){

        $ruls = [
            'email' => 'required|string|email|max:255',
            'currentPassword' => $this->admin_ruls['password'],
            'newPassword' => $this->admin_ruls['password'],
            'passwordConf' => 'required|string|same:newPassword|max:255'
        ];
        $items = collect($request->all())->intersectByKeys($ruls)->toArray();

        $isValid = $this->isValid($items, $ruls, 'newPassword');

        if(! $isValid) return response()->json($this->getMessages(), 200);
        
        $hasMatch =  \Hash::check($items['currentPassword'], $admin->password);
        
        // return ['request_all' => $items, 'admin' => $admin, 'password_match' => $hasMatch];
        if( $admin->email == $items['email'] && $hasMatch){
            $admin = $admin->update(['password' => bcrypt($items['newPassword'])]);
            return response()->json(["admin" => $admin, "messages" => $this->getMessages()], 200);
        }
        /* send message fail */
        return response()->json(['status' => false ,'message' => "can\'t reset password", 'request_all' => $items, 'admin' => $admin, 'password_match' => $hasMatch, 'check' => $items['newPassword']], 200);
    }
    
    public function changeEmail(Request $request, Admin $admin){
        // $request['currentEmail'] = $admin->email;
        $ruls = [
            'currentEmail' => 'required|string|email|max:255',
            'newEmail' => $this->admin_ruls['email'],
            'passwordEmail' => $this->admin_ruls['password'],
        ];

        
        $items = collect($request->all())->intersectByKeys($ruls)->toArray();

        $isValid = $this->isValid($items, $ruls, 'newEmail');

        if(! $isValid) return response()->json($this->getMessages(), 200);

        $hasMatch =  \Hash::check($items['passwordEmail'], $admin->password);
        // return ['request_all' => $items, 'admin' => $admin, 'password_match' => $hasMatch];
        /* update user email and send message success */
        if( $admin->email == $items['currentEmail'] && $hasMatch){
            $admin = $admin->update(['email' => $items['newEmail']]);
            return response()->json(["admin" => $admin, "messages" => $this->getMessages()], 200);
        }
        /* send message fail */
        return response()->json(['status' => false ,'message' => "can\'t reset email", 'request_all' => $items, 'admin' => $admin, 'password_match' => $hasMatch, 'check' => $items['currentEmail']], 200);
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