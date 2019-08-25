<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model
{
    protected  $fillable = [
    	'user_id', 'name', 'eventType', 'email','date', 'slug', 'confirmed',
    	'location','address','descriptions','phone', 'published_at'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
