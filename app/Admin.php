<?php

namespace App;

// use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable implements JWTSubject

{
    //

    protected $guard = "admin";
    
	protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token', 'roles'
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
        return $this->belongsToMany('App\Role','admin_roles', 'admin_id');
    }

    public function replayMessage()
    {
        return $this->hasMany('App\ReplayMessage', 'user_id');
    }

    public function getAdminWithAuthority($admin = false){
        $authority = $admin? $admin->roles()->first()->only(['id', 'name', 'slug', 'permissions'])
                            :$this->roles()->first()->only(['id', 'name', 'slug', 'permissions']);
        return $authority;
    }

    public function respondWithToken($token)
    {
        
        return [
            'status' => true,
            'authority' => $this->getAdminWithAuthority(),
            'user' => $this->only(['id', 'name', 'email']),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ];
    }
}
