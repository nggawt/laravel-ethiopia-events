<?php

namespace App\Repo\traits;

trait Messages{

	protected  $messages = [];
	
    public function setMessages($type, $target, $message){

        if(! isset($this->messages[$type])) $this->messages[$type] = [];
        if(! isset($this->messages[$type][$target])) $this->messages[$type][$target] = [];

        array_push($this->messages[$type][$target], $message);
    }

    public function getMessages(){
        return $this->messages;
    }

    public function setErrorsMessages($validator){

        foreach ($validator->errors()->get("*") as $key => $value) {

            foreach ($value as $message) {
                $msg = [$key => $message];
                $this->setMessages('errors', $key, $msg);
            }
        }
    }

    public function setSuccessMessages(array $inputs = []){

        foreach ($inputs as $key => $value) {

            $msg = [$key => "עודכן בהצלחה"];
            $this->setMessages('success', $key, $msg);
        }
    }
}