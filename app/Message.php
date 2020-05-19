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
        'user_id', 
        'email_from', 
        'email_to', 
        'name', 
        'subject', 
        'body', 
        'area', 
        'city', 
        'phone', 
        'date',
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
        return $this->hasMany('App\ReplayMessage');
    }
}
