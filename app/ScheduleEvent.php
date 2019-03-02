<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model
{
    protected  $fillable = ['user_id', 'name', 'eventType', 'email','date','location','address','description','phone', 'published_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
