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
            'name' => "ngga",
            'title' => "test",
            'email' => "nggawt100@gmail.com",
            'body' => "testing mail app",
            'date' => Carbon::now(),
        ];
        Message::create($msgs);

        $msgs = [
            'user_id' => 1,
            'name' => "buzzi",
            'title' => "test buzz",
            'email' => "buzzi@test.com",
            'body' => "testing mail app buzzi",
            'date' => Carbon::now(),
        ];
        Message::create($msgs);

        $msgs = [
            'user_id' => null,
            'name' => "nati",
            'title' => "test",
            'email' => "nati@test.com",
            'body' => "testing mail app nati",
            'date' => Carbon::now(),
        ];
        Message::create($msgs);
    }
}
