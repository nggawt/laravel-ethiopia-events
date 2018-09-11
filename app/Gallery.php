<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable  = ['costumer_id', 'image', 'video'];

    public function costumer()
    {
        return $this->hasOne('App\Costumer');
    }
}
