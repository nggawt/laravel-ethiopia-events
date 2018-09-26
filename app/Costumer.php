<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    protected  $fillable =['user_id', 'company', 'businessType', 'contact','title','loggo','discription','email','tel','address','deals', 'published_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function gallery()
    {
        return $this->hasOne('App\Gallery');
    }
}
