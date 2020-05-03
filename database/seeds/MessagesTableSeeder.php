<?php

use Illuminate\Database\Seeder;
use App\Message;
use Carbon\Carbon;

class MessagesTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $msgs = [
            'user_id' => 1,
            'email_from' => "nggawt100@gmail.com",
            'email_to' => "test@test.com",
            'name' => "ngga",
            'subject' => "test",
            'body' => "testing mail app",
            'area' => "center",
            'city' => "tl",
            'phone' => "0545553311",
            'date' => Carbon::now(),
        ];
        Message::create($msgs);

        $msgs = [
            'user_id' => 2,
            'email_from' => "buzzi@test.com",
            'email_to' => "test@test.com",
            'name' => "buzzi",
            'subject' => "test buzz",
            'body' => "testing mail app buzzi",
            'area' => "center",
            'city' => "tl",
            'phone' => "0545553322",
            'date' => Carbon::now(),
        ];
        Message::create($msgs);

        $msgs = [
            'user_id' => 3,
            'email_from' => "nati@test.com",
            'email_to' => "test@test.com",
            'name' => "nati",
            'subject' => "test",
            'body' => "testing mail app nati",
            'area' => "east",
            'city' => "tl",
            'phone' => "0545554422",
            'date' => Carbon::now(),
        ];
        Message::create($msgs);
    }
}
