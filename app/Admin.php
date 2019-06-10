<?php

namespace App;

// use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable implements JWTSubject

{
    //
	protected $fillable = [
        'name', 'email', 'password'
    ];

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

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_admins');
    }
}
