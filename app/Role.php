<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'permissions'
    ];

    public function admins(){
	    return $this->belongsToMany('App\Admin');
    }

    public function getPermissionsAttribute($permission){
    	return json_decode($permission, true);
    }
}
