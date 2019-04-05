<?php

namespace App\Http\Controllers;


use App\Events\MessagesEvents;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\SandMailToEe;
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
            'about' => 'string|min:12',
            'tel' => 'digits_between:8,10',
        ];

    function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getLogin', 'getUserLogged', 'store', 'destroy', 'contact']]);
       
    }

    public function contact(Request $request, User $user){

        $user = isset($user)? $user: auth()->user();
        $this->validate($request,[
            'name' => 'string|min:3',
            'email' => $this->user_ruls['email'],
            'phone' => $this->user_ruls['tel'],
            'area' => $this->user_ruls['city'],
            'city' => $this->user_ruls['city'],
            'msg_subject' => $this->user_ruls['area'],
            'message' => 'required|string|min:12',
        ]);
        
        SendEmailJob::dispatch($request->all(), SandMailToEe::class);
        $user_id = $user['id']? $user['id']: $user->id? $user->id: false;
        $msgs = [
            'user_id' => $user_id? $user_id: 1,
            'name' => $user->name? $user->name: $request['name'],
            'title' => "your mail: ". $request['msg_subject'] . " was sent.",
            'body' => $request['message'],
            'date' => Carbon::now(),
        ];
        event(new MessagesEvents($msgs));
        return $request;
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

        return response()->json($this->respondWithToken($token),200);
    }

    public function changePassword(Request $req, User $user){

        $ruls = [
            'email' => $this->user_ruls['email'],
            'currentPassword' => $this->user_ruls['password'],
            'newPassword' => $this->user_ruls['password'],
            'passwordConf' => 'required|string|same:newPassword'
        ];

        $items = collect($req->all())->intersectByKeys($ruls)->toArray();

        $isValid = $this->isValid($items, $ruls, 'newPassword');

        if(! $isValid) return response()->json($this->getMessages(), 200);
        
        $hasMatch =  \Hash::check($req->only('currentPassword')['currentPassword'], $user->password);

        if($user->email == $req['email'] && $hasMatch){
            return response()->json($this->getMessages(), 200);
            return response()->json(["user" => $user, "passwordMatch" => $hasMatch, "messages" => $this->getMessages()], 200);
        }
        return response()->json($this->getMessages(), 200);
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

        if($user->email == $req['email']){
            return response()->json(["user" => $user, "messages" => $this->getMessages()], 200);
        }
        return response()->json($this->getMessages(), 200);
    }

    
    private function isValid(array $inputs = [], array $rules = [], $item){
        $rules = count($rules)? $rules: $this->itemsRule;
        $validator = \Validator::make($inputs, $rules);

        $validator->fails()? $this->setErrorsMessages($validator): $this->setSuccessMessages([$item => $inputs[$item]]);
        //return true;
        return $validator->fails()? false:true;
    }

    public function getLogout(){
        Auth::logout();
        return redirect()->route('home');
    }

    public function store(Request $req){
        
        $this->validate($req,$this->user_ruls);

        $credentials = request(['email', 'password']);
        $req['password'] =  bcrypt($req['password']);
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

    public function destroy(Request $request,User $user){

        $authUser = auth()->user();
        // $user = User::findOrfail($user);
        // return ["user" => $user];
        /***** validate user and delete*****/
        if($authUser->id == $user['id'] && $authUser->email == $user->email){
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
