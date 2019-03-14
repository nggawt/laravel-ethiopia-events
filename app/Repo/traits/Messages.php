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
}