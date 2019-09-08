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
	    $namespace = '\\App\\'. $modelName;

	    $model = $namespace::where('id', $id)->first();
	    $user = ($model && $modelName != "User")? $model->user: $model;

        $record = [
                //'user_id' => $user->id, //auth()->user() ? auth()->user()->id : NULL,
                // 'session' => isset($request->session())? $request->session()->all():NULL,
                'origin' => request()->headers->get('origin'),
                'ip' => request()->server('REMOTE_ADDR'),
                'email' => $request->email,
                'token' => $request->token,
                'banned_until' => Carbon::now()->addDays(14),
                'user_agent' => request()->server('HTTP_USER_AGENT')
            ];


        if($user && ! empty($user)){	
            $record['user_id'] = $user->id;
            $record['email'] = $user->email;
        }
        // return ['user' => $user, 'model user' => $model, 'request all' => $request->all()];
        $isForbidden = $user->forbidden;

        if(! empty($isForbidden)){
        	 $banned_days = now()->diffInDays($isForbidden->banned_until);
        	return response()->json(['message' => 'user was alredy banned for 14 days. days left: '.$banned_days.' '.str_plural('day', $banned_days).'.',
                                     'bann' => $isForbidden, 'user' => $user], 200);
        }

        $forbidden = Forbidden_user::create($record);
        return response()->json(['message' => 'user was succesfully banned for 14 days!',
                                     'forbidden' => $forbidden, 'user' => $user], 200);
    }

    public function unbanned(Request $request, $id){
    	// create namespace
    	// init model
    	// find item on model
    	// unbanned user

		//    $modelName =  ucwords($request['model']);
		//    $namespace = '\\App\\'. $modelName;

		//    $model = $namespace::where('id', $id)->first();

		//    $user = ($model && $modelName != "User")? $model->user: $user;

        $user = \App\User::findOrFail($id);

        $forbidden = $user->forbidden;

        if($forbidden && ! empty($forbidden)) $forbidden->delete();

        return response()->json(['message' => 'user was succesfully unbanneded!',
                                     'forbidden' => $forbidden, 'user' => $user], 200);

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
