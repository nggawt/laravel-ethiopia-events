<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAdmin extends Model
{
    protected $fillable = [
        'admin_id', 'role_id'
    ];
}
