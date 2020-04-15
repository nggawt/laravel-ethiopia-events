<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
	use SoftDeletes;
    
    // protected $dates = [
    //     'created_at'
    // ];

	protected $fillable = [
        'user_id', 'name', 'email', 'title', 'body', 'date'
    ];

    protected $casts = [
        'favorites' => 'boolean',
        'created_at' => 'datetime',
	];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function replay()
    {
        return $this->hasOne('App\ReplayMessage');
    }
}
