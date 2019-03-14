<?php

namespace App\Http\Controllers;

use App\ScheduleEvent;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEvents;
use App\Repo\traits\Messages;

class ScheduleEventController extends Controller
{
    use Messages;
    private $itemsRule = [
        "name" => "required|string|min:3|email|max:7",
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

        $val = $this->valInputs($request->all());
        //if(! $val) 
            return $this->getMessages();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ScheduleEvent  $scheduleEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleEvent $scheduleEvent)
    {
        if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        return ["updated" => $request->all()];
        
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

    protected function valInputs(array $inputs = []){

        $keys = array_keys($this->itemsRule);
        $iputCollect = collect($inputs)->except($keys);

        if(count($iputCollect)){
            $msg = "Blocked User";

            $msg = [key($iputCollect->toArray()) => $msg];
            $this->setMessages('errors', key($iputCollect->toArray()), $msg);
            return false;
        }

        $validator = \Validator::make($inputs, $this->itemsRule);
        $validator->fails()? $this->getErrorsMessages($validator): $this->getSuccessMessages($inputs);

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
}
