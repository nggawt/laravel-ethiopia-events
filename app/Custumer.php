<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected  $fillable =[
        'user_id', 'company', 'businessType', 'content', 'contact',
        'title','loggo','descriptions','email','tel',
        'address','deals','confirmed', 'published_at'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
	];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function gallery()
    {
        return $this->hasOne('App\Gallery');
    }
}
