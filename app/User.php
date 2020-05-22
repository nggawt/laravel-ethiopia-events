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
        'name', 'email', 'password', 'city', 'area', 'tel', 'about'
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
            'type' => 'user',
            'user' => $this->formatedUser(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    public function formatedUser(){

        $user = array(
            'id' => $this->id,
            'type' => 'user',
            'name' => $this->name,
            'email' => $this->email,
            'tel' => $this->tel,
            'about' => $this->about,
            'area' => $this->area,
            'city' => $this->city,
        );

        if($this->customer && $this->customer->count()){
            $user['customer'] = $this->customer->only(['id', 'user_id', 'company', 'businessType', 'title', 'contact', 'descritions']);
        } 
            
        if($this->events && $this->events->count()) $user['events'] = $this->events;
        if($this->messages && $this->messages->count()) $user['messages'] = $this->messages;
        return $user;
    }
}
