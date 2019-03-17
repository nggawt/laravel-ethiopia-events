<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvents;
use App\Repo\traits\Messages;
use App\ScheduleEvent;
use Illuminate\Http\Request;

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
        if(! (\Auth::check())) return response()->json(['error' => 'Unauthorized'], 401);
        
        $req = $request->all();
        $val = $this->valInputs($req);

        if(! $val) return $this->getMessages();
        $req['user_id'] = auth()->user()->id;
        ScheduleEvent::create($req);

        return response()->json($this->getMessages(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleEvent $scheduleEvent, $id)
    {
        if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        $requestAll = $request->all();

        // $isBadRequest = $this->badRequest(collect($requestAll)->except('_method'));
        // if($isBadRequest) return response()->json($this->getMessages(), 200);

        $rules = collect($this->itemsRule)->intersectByKeys($requestAll)->toArray();
        $items = collect($requestAll)->intersectByKeys($this->itemsRule)->toArray();
        $isValid = $this->valInputs($items, $rules);

        if(! $isValid) return $this->getMessages();
        $scheduleEvent->find($id)->update($items);
        //$scheduleEvent->update($items);
        return response()->json($this->getMessages(), 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduleEvent $scheduleEvent, $id)
    {
        if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        
        return ['dd' => $scheduleEvent::find($id)];
    }

    protected function valInputs(array $inputs = [], array $rules = []){

        $isBadRequest = $this->badRequest($inputs);
        if($isBadRequest) return false;
        return $this->isValid($inputs, $rules);
    }

    private function isValid(array $inputs = [], array $rules = []){
        $rules = count($rules)? $rules: $this->itemsRule;
        $validator = \Validator::make($inputs, $rules);
        $validator->fails()? $this->getErrorsMessages($validator): $this->getSuccessMessages($inputs);
        return true;
        return $validator->fails()? false:true;
    }

    private function getErrorsMessages($validator){

        foreach ($validator->errors()->get("*") as $key => $value) {

            foreach ($value as $message) {
                $msg = [$key => $message];
                $this->setMessages('errors', $key, $msg);
            }
        }
    }

    private function getSuccessMessages(array $inputs = []){

        foreach ($inputs as $key => $value) {

            $msg = [$key => "עודכן בהצלחה"];
            $this->setMessages('success', $key, $msg);
        }
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
