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
        "date" => "required",//|numric|exact:10
        "email" => "required",
        "phone" => "required",
        "location" => "required|string|min:3",
        "address" => "required|string|min:6",
        "description" => "required|string|min:12"
    ];

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
     * Display the specified resource.
     *
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleEvent $scheduleEvent)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(! (Auth::check())) return response()->json(['error' => 'Unauthorized'], 401);
        
        $req = collect($request->all())->except('_method');
        if(count($req) < 1) return response()->json(["errors" => ["bad_request" => ['message' => "bad resquest",'type' => "errors"]]]);
        
        $val = $this->valInputs($req->toArray());

        if(! $val) return $this->getMessages();
        $req['user_id'] = auth()->user()->id;
        //ScheduleEvent::create($req);

        // \Mail::to('nggawt100@gmail.com')->send(
        //     new Event_created($req)
        // );

        SendEmailJob::dispatch($req, Event_created::class);//->onConnection('database');//->onQueue('default');

        $msgs = [
            'user_id' => $req['user_id'],
            'name' => auth()->user()->name,
            'title' => "your event: " . $req['eventType'] ." was created",
            'body' => $req['description'],
            'date' => Carbon::now(),
        ];
        event(new MessagesEvents($msgs));

        // dispatch(new SendEmailJob($req));
        // SendEmailJob::dispatch(['buzz' => "wt"])
        //         ->delay(Carbon::now()->addSeconds(5));
        return response()->json($this->getMessages(), 200);
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
        if(! Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        
        $requestAll = collect($request->all())->except('_method');
        if(count($requestAll) < 1) return response()->json(["errors" => ["bad_request" => ['message' => "bad resquest",'type' => "errors"]]]);
        // $isBadRequest = $this->badRequest(collect($requestAll)->except('_method'));
        // if($isBadRequest) return response()->json($this->getMessages(), 200);

        $rules = collect($this->itemsRule)->intersectByKeys($requestAll)->toArray();
        $items = $requestAll->intersectByKeys($this->itemsRule)->toArray();
        $isValid = $this->valInputs($items, $rules);

        return ['requestAll' => $requestAll, 'rules' => $rules, 'items' => $items];

        if(! $isValid) return $this->getMessages();
        // $scheduleEvent->find($id)->update($items);
        $event->update($items);
        return response()->json($this->getMessages(), 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduleEvent $event)
    {
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
