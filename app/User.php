<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tel', 'area', 'about', 'city'
    ];

    protected $dates = [
        'banned_until'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

     public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // protected function setPasswordAttribute($value){

    //     $this->attributes['password'] = bcrypt($value);
    // }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function customer()
    {
        return $this->hasOne('App\Customer');
    }

    public function events()
    {
        return $this->hasMany('App\ScheduleEvent');
    }


    public function messages()
    {
        return $this->hasMany('App\Message', 'email', 'email');
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    public function banned()
    {
        $this->banned_until = Carbon::now()->addDays(14);
        $this->update();
    }

    public function unbanned()
    {
        $this->banned_until = null;
        $this->update();
    }
}
