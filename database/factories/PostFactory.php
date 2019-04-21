<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
use App\User;

$factory->define(App\Post::class, function (Faker $faker) {
	$posts = [

		["title" => "אתר מעולה למתחתנים",
				"body" => "
					לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט למרקוח איבן , כלרשט מיחוצים. קלאצי הועניב היושבב שערש שמחויט - שלושע ותלברו
		
				לורם איפסום דולור סיט אמט לורם איפסום דולור סיט אמט קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי סחטיר בלובק. תצטנפל בלינדו למרקל אס לכימפו, דול, צוט ומעיוט - לפתיעם ברשג - ולתיעם גדדיש. קוויז דומור ליאמום בלינך רוגצה. לפמעט
		
				לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי הועניב היושבב שמחויט - שלושע ותלברו חשלו שעותלשך וחאית נובש ערששף. זותה מנק הבקיץ אפאח דלאמת כאנה ניצאחו נמרגי שהכים תוק, הדש שנרא התידם הכייר וק.
		
				קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי סחטיר בלובק. תצטנפל בלינדו למרקל אס לכימפו, דול, צוט ומעיוט - לפתיעם ברשג - ולתיעם גדדיש. קוויז דומור ליאמום בלינך רוגצה. לפמעט
		
				לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי הועניב היושבב שערש שמחויט - שלושע ותלברו חשלו שעותלשך וחאית נובש ערששף. זותה מנק הבקיץ אפאח דלאמת כאנה ניצאחו נמרגי תוק, הדש שנרא התידם .
		
				לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט איבן איף, ברומץ כלרשט מיחוצים. קלאצי הועניב היושבב - שלושע ותלברו חשלו שעותלשך וחאית נובש ערששף. זותה מנק הבקיץ אפאח דלאמת , ניצאחו נמרגי שהכים תוק, הדש שנרא התידם הכייר וק.
				"
		],
		["title" => "השינוי כבר כאן אתיופיה אירועים",
				"body" => "
								לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט למרקוח איבן , כלרשט מיחוצים. קלאצי הועניב היושבב שערש שמחויט - שלושע ותלברו
		
					לורם איפסום דולור סיט אמט לורם איפסום דולור סיט אמט לורם איפסום דולור סיט אמט, ק ונסקטורר אלית קולהע צופעט איבן איף, ברומץ כלרשט מיחוצים. קלאצי הועניב היושבב שערש שמחויט - שלושע ותלברו
		
					לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי הועניב היושבב שמחויט - שלושע ותלברו חשלו שעותלשך וחאית נובש ערששף. זותה מנק הבקיץ אפאח דלאמת כאנה ניצאחו נמרגי שהכים תוק, הדש שנרא התידם הכייר וק.
		
					קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי סחטיר בלובק. תצטנפל בלינדו למרקל אס לכימפו, דול, צוט ומעיוט - לפתיעם ברשג - ולתיעם גדדיש. קוויז דומור ליאמום בלינך רוגצה. לפמעט
		
					לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי הועניב היושבב שערש שמחויט - שלושע ותלברו חשלו שעותלשך וחאית נובש ערששף. זותה מנק הבקיץ אפאח דלאמת כאנה ניצאחו נמרגי תוק, הדש שנרא התידם .
		
					קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי סחטיר בלובק. תצטנפל בלינדו למרקל אס לכימפו, דול, צוט ומעיוט - לפתיעם ברשג - ולתיעם גדדיש. קוויז דומור ליאמום בלינך רוגצה. לפמעט
				",
			],

		["title" => "מה חדש באירועים",
				"body" => "
					לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית קונדימנטום קורוס בליקרה, נונסטי קלובר בריקנה סטום, לפריקך תצטריק לרטי.
		
					לקטוס וואל אאוגו וסטיבולום סוליסי טידום בעליק. קונדימנטום קורוס בליקרה, נונסטי קלובר בריקנה סטום, לפריקך תצטריק לרטי.
					נולום ארווס סאפיאן - פוסיליס קוויס, אקווזמן קוואזי במר מודוף. אודיפו בלאסטיק מונופ
					כאנה ניצאחו נמרגי שהכים תוק, הדש שנרא התידם הכייר וק.
					ניצאחו נמרגי נולום ארווס סאפיאן נולום ארווס סאפיאן - פוסיליס קוויס, אקווזמן קוואזי במר מודוף. אודיפו בלאסטיק מונופץ
		
					מנסוטו צמלח לביקו ננבי, צמוקו בלוקריה שיצמה ברורק. קולהע צופעט למרקוח
				"]
	];

	$users = User::all();
	$user = $users[rand(1, count($users) - 1)];

	$phone = ['0505564747', '0525564744', '0555564747', '0505564447'];

	$userPublicName = ['jhon', 'benny', 'david and dana', "שלום", "אורן", "דויד"];
	// $userName = $userPublicName[rand(0, count($userPublicName) -1)];
	

	// $title = ["wedding","bar-mitzvah", "hina", "wedding"];

	// $address = ["ashdod haetzel 70","natanya harov 55", "rehovot havtzelet 12"];
	// $description = ["אירוע פעם בחיים בואו להיות חלק ממנו","מזמינים אותכם לחתונה שלנו", "נשמח לראותכם באירוע"];
	$postsToCreate = [];
	foreach ($posts as $post) {

		$uId = rand(0, count($userPublicName) -1);

		$userArr = [$user, $uId, $user, $uId];
		$dbUser = rand(0, count($userArr) -1);

		if(isset($dbUser->name)){
			$userName = $user->name;
			$id = $user->id;
		}else{
			$userName = $userPublicName[$uId];
			$id = $uId;
		}

		$postsToCreate[] =  [
						'user_id' => $id,
						'name' => $userName,
						'title' => $post['title'],
						'body' => $post['body'],
						'date' => Carbon::now()
		];
	}
	return $postsToCreate;
});
	