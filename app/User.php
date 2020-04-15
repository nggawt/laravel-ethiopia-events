<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'isAdmin' => 'boolean',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
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

    public function forbidden()
    {
        return $this->hasOne('App\Forbidden_user', 'email', 'email');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function replayMessage()
    {
        return $this->hasMany('App\ReplayMessage');
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    public function respondWithToken($token)
    {
        
        return [
            'status' => true,
            'user' => $this->getUser(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    private function getUser(){

        
        $customer = $this->customer;
        $events = $this->events;
        $messages = $this->messages;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'tel' => $this->tel,
            'about' => $this->about,
            'area' => $this->area,
            'city' => $this->city,
            'messages' => $messages? $messages: false,
            'customer' => $customer? $customer->only(['id', 'user_id', 'company', 'businessType', 'title', 'contact', 'discription']): false,
            'events' => $events? $events: false
        ];
    }
}
