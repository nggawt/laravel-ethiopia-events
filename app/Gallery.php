<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable  = ['customer_id', 'image', 'video'];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
