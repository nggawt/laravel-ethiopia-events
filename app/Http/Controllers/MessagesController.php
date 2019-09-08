<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Repo\traits\Messages;

use App\Events\MessagesEvents;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\SandMailToEe;
use Illuminate\Support\Carbon;
use App\User;
use App\Http\Requests\ValidateContactRequest;

use Illuminate\Support\Facades\Log;
use App\Forbidden_user;

class MessagesController extends Controller
{
    use Messages;

    private $itemsRule = [
        "name" => "required|string|min:3",//|email|max:7",
        "title" => "required|string|min:3|max:50",
        "body" => "required|string|min:12",
    ];
    
       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin');
    }

    public function index(Message $message) {
    	// $this->authorize('posts', $post);
    	// $this->authorize('view', $post);
    	// if(Gate::can('view', 2)){
			return $message->withTrashed()->get();//$this->getConfirmedPosts(Post::all());
    	// }

	}

    public function contact(ValidateContactRequest $request, User $user){

        $user = isset($user)? $user: auth('api')->user();
        
        $email_from = $request->email;
        $request['email_to'] = "nggawt10@gmail.com";
        
        SendEmailJob::dispatch($request->all(), SandMailToEe::class);

        $user_id = $user['id']? $user['id']: $user->id? $user->id: null;
        $msgs = [
            'user_id' => $user_id,
            'name' => $user->name? $user->name: $request['name'],
            'title' => $request['subject'],
            'email' => $request['email'],
            'body' => $request['message'],
            'date' => Carbon::now(),
        ];

        event(new MessagesEvents($msgs));
        return response()->json(["request" => $request->all(), 'email_from' => $email_from, 'email_to' => $request->email_to],200);
    }

    public function replay(Request $request){
        return $request->all();
    }

    public function update (Request $request, Message $message){

        $itemFavo = $request->only('favorites');
        if(! empty($itemFavo) && ($itemFavo['favorites'] === 0 || $itemFavo['favorites'] == 1)){
            $message->favorites = $itemFavo['favorites'];
            $message->update();
            return response()->json(['message' => 'mail was succesfully update!'],200);
        } 
        return response()->json(['message' => 'somthing went wrong with your request!', gettype($request)],200);
    }

    public function trashedItems ($id){
        $trashed = Message::onlyTrashed()
                ->where('id', $id)
                ->restore();

        if($trashed){
            return response()->json(['message' => 'mail was succesfully restored!', 'item' => $trashed],200);
        }
        return response()->json(['message' => 'somthing went wrong with your request!'],200);
    }

    

    public function banned(Request $request, $id){

        $message = Message::withTrashed()
                ->where('id', $id)
                ->first();

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
        $user = $message? $message->user: false;
        if($user && ! empty($user)){
            $record['user_id'] = $user->id;
            $record['email'] = $user->email;
        }
        $forbidden = Forbidden_user::create($record);
        return response()->json(['message' => 'user was succesfully banned for 14 days!',
                                     'forbidden' => $forbidden], 200);
        // return response()->json(['message' => 'banned user was failed!', 'request' => $request->all(), 'user' => $user],200);
    }

    public function destroy ($id){
        $message = Message::withTrashed()
                ->where('id', $id)
                ->firstOrfail();

        if (! $message->trashed()) {
            $message->delete();
            return response()->json(['message' => 'mail was succesfully trashed!'],200);
        }else{
            // $message->forceDelete();
            return response()->json(['message' => 'mail was succesfully deleted!'],200);
        }
    }
}
