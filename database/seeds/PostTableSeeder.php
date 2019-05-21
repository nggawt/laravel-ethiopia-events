<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;
use App\Post;

class PostTableSeeder extends Seeder{


	public function run() {
		$posts = [

			[
				"title" => "אתר מעולה למתחתנים",
				"body" => "
					דרמת פשע זוכת פרסים מאוסטרליה.

    אחרי שאמו מתה ממנת יתר, ג'ושוע יוצר קשר עם משפחתו המרוחקת כדי למצוא מקום לגור בו. כשהוא מגיע לביתם הלא מתפקד הוא מתחיל להבין את יחסי הכוחות, להכיר את הסבתא הדומיננטית, את הדוד פופ קודי שחי במחבוא, את שאר הדודים ואת השוטרים המושחתים, כאשר המתח בין המשפחה למשטרה מגיע לשיאו. 
    עד מהרה מוצא ג'ושוע את עצמו בלב המאבק העקוב מדם, וחייב ללמוד את יחסי הכוחות והשחקנים השונים אם הוא רוצה לשרוד.
    
    עם ג'קי וויבר (
    \"אופטימיות היא שם המשחק
    \"), שהייתה מועמדת לאוסקר ולגלובוס הזהב על הופעתה בסרט, גאי פירס (
    \"ממנטו
    \") וג'ואל אדג'רטון (\"לוחם
    \").
    הסרט השתתף באירועי קולנוע רבים וחשובים בעולם, זכה במועמדויות ופרסים, ביניהם פרס חבר השופטים של פסטיבל 'סאנדנס'. 
    ב- 2016 עלתה ברשת TNT סדרה אמריקאית באותו שם המבוססת על הסרט.
				"
			],
			[
				"title" => "השינוי כבר כאן אתיופיה אירועים",
				"body" => "
					דרמה קומית זוכת פרסים בבימויו של רומן פולנסקי.

שני זוגות של הורים לילדים אשר התקוטטו אחד עם השני בפארק ציבורי, נפגשים ללבן ביניהם את העניינים בצורה תרבותית. למרות זאת, ככל שהביקור נמשך, הם הופכים יותר ויותר ילדותיים ואגריסיביים כלפי האחרים.

תצוגת משחק פנומלית של צוות השחקנים, ג'ודי פוסטר (\"שתיקת הכבשים\"), קייט ווינסלט (\"טיטאניק\"), ג'ון סי. ריילי (\"אחים חורגים\") וכריסטוף וולץ (\"ממזרים חסרי כבוד
\").
הסרט היה מועמד לשני פרסים בגלובוס הזהב של אותה השנה, ולפרסים רבים נוספים בפסטיבלים ברחבי תבל, בחלקם גם זכה, בין היתר בטקס הסזאר הצרפתי ובפסטיבל ונציה.
				"
			],
			[
				"title" => " חטיפה לאור יום ",
				"body" => "
					מותחן חטיפה בכיכובם של ברוס וויליס והנרי קאוויל (\"סופרמן\").

משפחתו של וויל שואו, ברוקר אמריקאי מצליח, נחטפת במהלך נופש בספרד. יש לו רק מספר שעות בכדי לאתר אותה, לחשוף קונספירציה ממשלתית ולעמוד על הקשר בין החטיפה לסודות המפתיעים שאביו הסתיר ממנו.
				"
			],
			[
				"title" => "הקריאה",
				"body" => "
					ממותחן פשע בכיכובה של האלי ברי.

מרכזנית בקו החירום המשטרתי נאלצת להתמודד עם פחדיה הגדולים ביותר כאשר היא נחלצת לעזרתה של נערה מתבגרת, הנרדפת בזמן אמת על ידי רוצח סדרתי.
				"
			],
			[
				"title" => "בובי Z",
				"body" => "
				מותחן פשע בכיכובו של פול ווקר (\"מהיר ועצבני\").

				חייל מארינס לשעבר מסתבך במהלך המאסר הממושך שלו ועומד בפני סכנת מוות, אם ישוחרר מתנאי הבידוד בהם הוא נמצא. 
				סוכן משטרתי מציע לו עסקה שתציל אותו מהתסבוכת: להתחזות לאדם בשם בובי Z, פלייבוי וסוחר סמים מפורסם שנעלם כמה שנים קודם לכן. התוכנית יוצאת לפועל אך מסתבכת, והאסיר המשוחרר מוצא עצמו בורח בלוויית בנו בן ה-6 של בובי מפני מאפיונר מקסיקני מסוכן, סוכנים פדראליים וכנופיית אופנוענים.

				עם לורנס פישבורן (\"מטריקס\") ואוליביה וויילד (הסדרה \"וייניל\").
				"
			]
		];

		$users = User::all();
		$user = $users[rand(1, count($users) - 1)];

		$phone = ['0505564747', '0525564744', '0555564747', '0505564447'];

		$userPublicName = ['jhon', 'benny', 'david and dana', "שלום", "אורן", "דויד"];
		
		$dt = Carbon::now();
		$thisMonth = $dt->month;
		foreach ($posts as $key => $post) {

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
			$dt->month = rand(1, ($thisMonth - 1));
			$dt->day = rand(1, 30);

			Post::create([
				'user_id' => $id,
				'confirmed' => rand(0, 1),
				'name' => $userName,
				'title' => $post['title'],
				'body' => $post['body'],
				'date' => $dt
			]);
		}
	}
}