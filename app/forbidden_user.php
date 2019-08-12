<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forbidden_user extends Model
{
    protected $fillable = [
        'user_id', 'email', 'origin', 'ip', 'token', 'session', 'user_agent', 'banned_until'
    ];

    protected $dates = [
        'banned_until'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

}
