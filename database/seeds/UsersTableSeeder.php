<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    //DB::table('users')->truncate();
       User::create([
            
            "name" => "הנהלה",
            "email" => "armonotlev1@walla.com",
            "area" => "צפון",
            "city" =>"אשקלון, ההסתדרות 40 (קניון לב אשקלון)",
            "about" => "ארמונות לב המחודשים 2012",
            "tel" => "08-6640450",
            "password" => bcrypt("qweqwe")
        ]);

       User::create([
        	
            "name" => "הנהלה",
            "email" => "benistal23@gmil.com",
            "city" => "רחובות",
            "area" => "שפלה",
            "about" => "ארמונות בינסטל ",
            "tel" => "08-8522668, 08822669",
            "password" => bcrypt("qweqwe")
        ]);
       
       User::create([
        	
            "name" =>  "הנהלה",
            "email" =>  "margalit3312@wall.com",
            "city" => "לוד",
            "area" => "שפלה",
            "tel" =>  "08-8566070, 057-3109340",
            "about" => "גולדן קראון",
            "password" => bcrypt("qweqwe")
        ]);

       User::create([
	        
	        "name" => "הנהלה",
	        "email" => "fakeEmail@kjk.com",
            "city" => "פתח תקווה",
            "area" => "מרכז",
	        "tel" => "08-6722256 ,077-270789",
            "about" => "סילבר אירועים ",
            "password" => bcrypt("qweqwe")
	    ]);
    }
}
