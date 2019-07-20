<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

	protected $fillable = [
        'user_id', 'name', 'title', 'body', 'date'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
