<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'name', 'title', 'body', 'date', 'slug'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
	];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // protected function getConfirmedAttribute($value){

    // }
}
