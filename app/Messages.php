<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{

	protected $fillable = [
        'user_id', 'name', 'title', 'body', 'date'
    ];

    public function message()
    {
        return $this->belongsTo('App\User');
    }
}
