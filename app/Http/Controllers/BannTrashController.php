<?php

namespace App\Http\Controllers;

use App\Forbidden_user;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BannTrashController extends Controller
{

   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin');
    }

    public function forbidden(){
    	return Forbidden_user::all();
    }

    public function banned(Request $request, $id){
    	// create namespace
    	// init model
    	// find item on model and trash
    	// banned user

	    $modelName =  ucwords($request['model']);
	    $model = $this->getModel($request);

	    $user = ($model && $modelName != "User" && $model->user_id)? $model->user: \App\User::where('email', $request['email'])->first();
	    // return ['user' => $user, 'model' => $model];
	    // if(!$user)  
        $record = [
            //'user_id' => $user->id, //auth()->user() ? auth()->user()->id : NULL,
            // 'session' => isset($request->session())? $request->session()->all():NULL,
    		'user_id' => (! empty($user) && $user->id)? $user->id: $request->user_id,
            'origin' => request()->headers->get('origin'),
            'ip' => request()->server('REMOTE_ADDR'),
            'email' => $user->email? $user->email: $request->email,
            'token' => $request->token,
            'banned_until' => $request->bannd_until?? Carbon::now()->addDays(14),
            'user_agent' => request()->server('HTTP_USER_AGENT')
        ];

        // return ['user' => $user, 'model user' => $model, 'request all' => $request->all()];
        $isForbidden = $user && ! empty($user)? $user->forbidden: false;

        if($isForbidden && ! empty($isForbidden)){
        	 $banned_days = now()->diffInDays($isForbidden->banned_until);
        	return response()->json(['message' => 'user was alredy banned for 14 days. days left: '.$banned_days.' '.str_plural('day', $banned_days).'.',
                                     'forbidden' => $isForbidden, 'user' => $user, 'status' => false], 200);
        }

        $forbidden = Forbidden_user::create($record);
        return response()->json(['message' => 'user was succesfully banned for 14 days!',
                                     'forbidden' => $forbidden, 'user' => $user, 'status' => true], 200);
    }

    protected function getModel($request){

    	$modelName =  ucwords($request['model']);
	    $namespace = '\\App\\'. $modelName;

	    return $namespace::findOrFail($request->id);
    }

    public function unbanned(Request $request, $id){
    	// create namespace
    	// init model
    	// find item on model
    	// unbanned user
    	$userFinder = $request['email']? 'email': 'user_id';


	   $modelName =  ucwords($request['model']); 

    	$status = false;
        $model = $this->getModel($request);
        $user = ($model && $modelName != "User")? $model->user:false; 

        if(! $user && $modelName != "User"){
        	$userFinder = $request['email']? 'email': 'id';
        	$finderValue = $userFinder == "id"? 'user_id': $userFinder;
        	$user = \App\User::where($userFinder, $request[$finderValue])->first();
        }else if($modelName == "User"){
        	$user = $model;
        }
        // $user =  ? $user = $model: '';
        // Forbidden_user::where('email', $request['email'])->first();
        $forbidden = ($user && ! empty($user))? $user->forbidden: false;
        $forbidden = $forbidden? $forbidden: Forbidden_user::where($userFinder, $request[$userFinder])->first();

        if($forbidden && ! empty($forbidden)){
        	$forbidden->delete();
        	$status = true;
        }

        return response()->json(['message' => 'user was succesfully unbanneded!',
                                     'forbidden' => $forbidden, 'model' => $model, 'user' => $user, 
                                     'status' => $status, 'forbidden all' => Forbidden_user::all()], 200);

        // return response()->json(['message' => 'banned user was failed!', 'request' => $request->all(), 'user' => $user],200);
    }

    public function untrash (Request $request, $id){
    	// create namespace
    	// init model
    	// find item on model that trashed and restore

        // $trashed = Message::onlyTrashed()
        //         ->where('id', $id)
        //         ->restore();

        if($trashed){
            return response()->json(['message' => 'mail was succesfully restored!', 'item' => $trashed], 200);
        }
        return response()->json(['message' => 'somthing went wrong with your request!'], 200);
    }

    public function trash(Request $request, $id){
     	// create namespace
    	// init model
    	// find item on model and trash

    	$model = '\\App\\'. ucwords($request['model'])::findOrFail($id);
        // $message = Message::withTrashed()
        //         ->where('id', $id)
        //         ->firstOrfail();

        if (! $message->trashed()) {
            $message->delete();
            return response()->json(['message' => 'mail was succesfully trashed!'], 200);
        }
        // $message->forceDelete();
        return response()->json(['message' => 'mail was succesfully deleted!'], 200);
    }
   
}
