<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplayMessage extends Model
{
    protected $fillable = [
        'content', 'user_id', 'message_id'//'title', 
    ];

    public function user(){
	    return $this->belongsTo('App\User');//belongsToMany
    }

    public function admin(){
	    return $this->belongsTo('App\Admin');//belongsToMany
    }

    public function message(){
	    return $this->belongsTo('App\Message');
    }
}
