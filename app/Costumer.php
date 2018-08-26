<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    protected  $fillable =['company', 'businessType', 'contact','title','loggo','discription','email','tel','address','deals', 'published_at'];
}
