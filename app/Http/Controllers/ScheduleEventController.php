<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreEvents;
use App\Events\MessagesEvents;
use App\Jobs\SendEmailJob;
use App\Mail\Event_created;
use App\Repo\traits\Messages;
use App\ScheduleEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleEventController extends Controller
{
    use Messages;

    private $itemsRule = [
        "name" => "required|string|min:3",//|email|max:7",
        "eventType" => "required|string|min:3",
        "date" => "required|date",//|numric|exact:10
        "email" => "required",
        "phone" => "required",
        "location" => "required|string|min:3",
        "address" => "required|string|min:6",
        "descriptions" => "required|string|min:12",
        "confirmed" => "boolean"
    ];

    public function __construct()
    {
        // $this->middleware('cors');
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(ScheduleEvent::all()->toArray(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $items = collect($request->all())->except('_method')->intersectByKeys($this->itemsRule)->toArray();
        $validty =\Validator::make($items, $this->itemsRule);

        if($validty->fails()) return response()->json([$request->all(), "message" => "you have an errors!", 'errors' => $validty->errors()->all(), "status" => false], 200);
        
        SendEmailJob::dispatch($items, Event_created::class);//->onConnection('database');//->onQueue('default');
        $items['user_id'] = isset($items['user_id'])? $items['user_id']: auth('api')->user()->id;
        $msgs = [
            'user_id' => $items['user_id'],
            'name' => $items['name'],
            'email' => $items['email'],
            'title' => "your event: " . $items['eventType'] ." was succesfuly created!",
            'body' => $items['descriptions'],
            'date' => $items['date'],
        ];
        event(new MessagesEvents($msgs));

        $event = ScheduleEvent::create($items);
        return response()->json(["message" => "you event succesfully created!", 'event' => $event, "status" => true], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleEvent $event)
    {
        // if(! Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        
        // $requestAll = collect($request->all())->except('_method');
        // if(count($requestAll) < 1) return response()->json(["errors" => ["bad_request" => ['message' => "bad resquest",'type' => "errors"]]]);

        $rules = collect($this->itemsRule)->intersectByKeys($request->all())->toArray();
        // $items = $requestAll->intersectByKeys($this->itemsRule)->toArray();
        // $isValid = $this->valInputs($items, $rules);

        // return ['requestAll' => $requestAll, 'rules' => $rules, 'items' => $items];

        // if(! $isValid) return $this->getMessages();
        // $scheduleEvent->find($id)->update($items);
        $items = $this->validate($request, $rules);
        $event->update($items);
        return response()->json(["message" => "you event succesfully created!", 'items' => $items, "status" => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,ScheduleEvent $event)

    {   return ["request" => $request->all(), "event" => $event];
        if(! Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        
        return ['dd' => $event->delete()];
    }

    protected function valInputs(array $inputs = [], array $rules = []){

        $isBadRequest = $this->badRequest($inputs);
        if($isBadRequest) return false;
        return $this->isValid($inputs, $rules);
    }

    private function isValid(array $inputs = [], array $rules = []){
        $rules = count($rules)? $rules: $this->itemsRule;
        $validator = \Validator::make($inputs, $rules);
        $validator->fails()? $this->setErrorsMessages($validator): $this->setSuccessMessages($inputs);
        //return true;
        return $validator->fails()? false:true;
    }

    

    private function badRequest($inp){
        $keys = array_keys($this->itemsRule);
        $iputCollect = collect($inp)->except($keys);

        if($iputCollect->count()){
            $msg = "Blocked User";

            $msg = [key($iputCollect->toArray()) => $msg];
            $this->setMessages('errors', key($iputCollect->toArray()), $msg);
            return true;
        }
        return false;
    }
}
