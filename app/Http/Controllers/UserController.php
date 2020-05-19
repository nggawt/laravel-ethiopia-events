<?php

namespace App\Http\Controllers;


use App\Events\MessagesEvents;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\SendMailToEe;
use App\Post;
use App\Repo\traits\Messages;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use Messages;
    private $user_ruls = [

            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
            'passwordConfirm' => 'required|string|same:password|max:255',
            'city' => 'string|min:3|max:30',
            'area' => 'required|string|min:3|max:30',
            'banned_until' =>'date|nullable',
            'about' => 'string|min:12',
            'tel' => 'digits_between:8,10',
            'isAdmin' => 'boolean',
        ];

    function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'store','update', 'destroy']]);
        // $this->middleware('auth:api', ['only' => ['store','update', 'destroy']]);
       
    }

    public function index(){
        // return User::all();
        if($status = Auth::guard('admin')->check()){
            return response()->json(User::all(), 200);//['status' => $status, 'user' => User::all()]
        } 
        return response()->json(['status' => $status],200);
    }

    public function store(Request $request){
        
        $this->validate($request,$this->user_ruls);

        // if(! $admin = auth('admin')->check()){
        //     return response()->json(['status' => "please log in as admin! ", $admin], 200);
        // }
        
        $credentials = $request->only(['email', 'password']);
        $request['password'] =  bcrypt($request['password']);
        $req = $request->except(['passwordConfirm']);

        $user = User::create($req);
        return response()->json(["message" => "user was created", "user" => $user],200);
    }

    public function update(Request $request, User $user){

        $requestItems = $request->except(['email', 'password']);

        $items = collect($requestItems)->intersectByKeys($this->user_ruls)->toArray();
        $ruls = collect($this->user_ruls)->intersectByKeys($items)->toArray();
        $isValid = $this->isValid($items, $ruls, 'update');

        /* send message fail if invalid */
        if(! $isValid) return response()->json(['status' => 'fail' ,$this->getMessages()], 200);

        /* update user and send message success */
        // return ['items' => $items, 'ruls' => $ruls];
        $updatedUser = $user->update($items);
        return response()->json(['status' => true, $this->getMessages(), 'items' => $items], 200);
    }

    public function destroy(Request $request,User $user){

        return ["request" => $request->all(), "user" => $user];
        $authUser = auth('api')->user();
        // $user = User::findOrfail($user);
        // return ["user" => $user];
        /***** validate user and delete*****/
        if($authUser->id == $user['id'] && $authUser->email == $user->email){
            // $user->delete();
            return response()->json(["message" => "user was deleted!"],200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function changePassword(Request $request, User $user){

        $ruls = [
            'email' => $this->user_ruls['email'],
            'currentPassword' => $this->user_ruls['password'],
            'newPassword' => $this->user_ruls['password'],
            'passwordConf' => 'required|string|same:newPassword'
        ];

        $items = collect($request->all())->intersectByKeys($ruls)->toArray();

        $isValid = $this->isValid($items, $ruls, 'newPassword');

        if(! $isValid) return response()->json($this->getMessages(), 200);
        
        $hasMatch =  \Hash::check($request->only('currentPassword')['currentPassword'], $user->password);

        if($user->email == $request['email'] && $hasMatch){
            /* update user password and send message success */
            return response()->json($this->getMessages(), 200);
        }
        /* send message fail */
        return response()->json(['status' => 'fail' ,$this->getMessages()], 200);
    }
    
    public function changeEmail(User $user, Request $req){

        $ruls = [
            'currentEmail' => $this->user_ruls['email'],
            'newEmail' => $this->user_ruls['email'],
            'passwordEmail' => $this->user_ruls['password'],
        ];

        $items = collect($req->all())->intersectByKeys($ruls)->toArray();

        $isValid = $this->isValid($items, $ruls, 'newEmail');

        if(! $isValid) return response()->json($this->getMessages(), 200);
        /* update user email and send message success */
        if($user->email == $req['email']){
            return response()->json(["user" => $user, "messages" => $this->getMessages()], 200);
        }
        /* send message fail */
        return response()->json(['status' => 'fail' ,$this->getMessages()], 200);
    }

    private function isValid(array $inputs = [], array $rules = [], $item){
        $rules = count($rules)? $rules: $this->user_ruls;
        $validator = \Validator::make($inputs, $rules);

        $validator->fails()? $this->setErrorsMessages($validator): $this->setSuccessMessages([$item => $item]);
        //return true;
        return $validator->fails()? false:true;
    }
 
}
