<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForbiddenUserController extends Controller
{
    //

    
    protected function bannedUser($filed, $user){
    	
         $dt = is_null($filed['banned_until'])? null: Carbon::parse($filed['banned_until']);
         $user->banned_until = $dt;

        // is_null($filed['banned_until'])? $user->unbanned(): $user->banned();
    }

}
