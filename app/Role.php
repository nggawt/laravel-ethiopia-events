<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'permisson'
    ];

    public function admins(){
	    return $this->belongsToMany('App\Admin');
    }
}
