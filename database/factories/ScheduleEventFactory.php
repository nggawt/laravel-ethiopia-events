<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
use App\User;

$factory->define(App\ScheduleEvent::class, function (Faker $faker) {
	
	$users = User::all();
	$user = $users[rand(1, count($users) - 1)];

	$phone = ['0505564747', '0525564744', '0555564747', '0505564447'];

	$userPublicName = ['jhon and doe', 'ofer and ora', 'benny and tova', 'david and dana'];
	// $userName = $userPublicName[rand(0, count($userPublicName) -1)];
	$uId = rand(1, count($users) -1);

	$userArr = [$user, $uId, $user, $uId];
	$dbUser = rand(0, count($userArr) -1);

	if(isset($dbUser->name)){
		$userName = $user->name;
		$id = $user->id;
	}else{
		$userName = $userPublicName[$uId];
		$id = $uId;
	}

	$dt = Carbon::now();

	$month = [1 ,2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

	$dt->month = $month[rand(0, count($month) -1)];
	$dt->day = rand(1, $dt->daysInMonth);
	
	$dt->hour = rand(18, 23);
	$dt->minute = 0;
	$dt->second = 0;

	/*
	$dt->year = 1975;
	$dt->month = 13;             // would force year++ and month = 1
	$dt->month = 5;
	$dt->day = 21;
	$dt->hour = 22;
	$dt->minute = 32;
	$dt->second = 5;
	*/

	$eventType = ["wedding","bar-mitzvah", "hina", "wedding"];
	$location = ["ashdod","natanya", "rehovot"];
	$address = ["ashdod haetzel 70","natanya harov 55", "rehovot havtzelet 12"];
	$description = ["אירוע פעם בחיים בואו להיות חלק ממנו","מזמינים אותכם לחתונה שלנו", "נשמח לראותכם באירוע"];

    return [
        'user_id' => $id,
        "confirmed" => rand(0, 1),
        'eventType' => $eventType[rand(0, count($eventType) - 1)],
        'name' => $userName,
        'date' => $dt,
        'email' => $faker->unique()->safeEmail,
        'location' => $location[rand(0, count($location) - 1)],
        'address' => $address[rand(0, count($address) - 1)],
        'phone' => $phone[rand(0, count($phone) - 1)],
        'description' => $description[rand(0, count($description) - 1)],
    ];
});
 