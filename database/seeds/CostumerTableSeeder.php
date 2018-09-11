<?php

use Illuminate\Database\Seeder;
use App\Costumer;
use Carbon\Carbon;

class CostumerTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Costumer::create([

            "user_id" =>  1,
            'company' => 'ארמונות לב',
            "businessType" => "halls events",
            "contact" => "הנהלה",
            "title" => "ארמונות לב המחודשים 2012",
            "loggo" => "./assets/pages/costumers/halls-events/palace-lev/loggo/123.png",
            "discription" => 'אולמות לב השוכנים בקניון לב באשקלון מתגאים בעיצוב חדש, איכותי ויוקרתי מאין כמוהו
             ובהנהלה חדשה, שמעניקה למקום מניסיונה העשיר. האחים לב, המפורסמים והפעילים בתחום הפקת האירועים
             בישראל,זה זמן רב מתחייבים לסטנדרטים הכי טובים באחריות.להפיק לכם את האירוע המושלם בשבילכם.

            ב"ארמונות לב" תוכלו לערוך חתונות, אירועי בר/בת מצווה,
             בריתות, טקסי חינה, מסיבות רבות משתתפים,ועוד. המקום ממתין לשירותכם, ובמיוחד לשדרוג האירוע שלכם,
             ציוד הגברה ותאורה ברמה הגבוהה ביותר. התפריט שיוגש באירוע שלכם ייבנה בשיתוף השף המקצועי, המנוסה
             והמפורסם של המקום, ובהתאם לצרכיכם הייחודיים. האחים לב, שלהם עשרות שנות פעילות בתחום וידע עשיר
            
            בהפקת אירועים, יחד עם הצוות המארח, הידוע ביחסו
             החם ללקוחות המקום, יעניקו את כל הידע והמקצועית שלהם לכם ולאורחיכם. יחד מייצרים כל אלו תוצאה שאין
             כמותה ואירוע מושלם ובלתי נשכח! כמו כן מספקים "ארמונות לב" שירות קייטרינג משובח עם תפריט מגוון,
            
            איכותי ונהדר עם מיטב המאכלים. השירות כולל משלוח עד הבית. בשטח האולם שב"ארמונות לב" מתקיים
             בערבי חג ובימי שישי יריד מזון שבועי בו נמכרים מאכלים מובחרים וטריים ממגוון סגנונות ועדות ובכל הטעמים,
             והכול במחירים עממיים. *כל המאכלים של "ארמונות לב" בהשגחת הרבנות אשקלון וניתן לקבל גם כשרות בד"ץ.
            ',
            "email" => "armonotlev1@walla.com",
            "tel" => "08-6640450",
            "address" =>"אשקלון, ההסתדרות 40 (קניון לב אשקלון)",
            "deals" => ""
        ]);
       Costumer::create([


            "user_id" =>  2,
        	"company" => "בניסטל",
            "businessType" => "halls events",
            "contact" => "הנהלה",
            "title" => "ארמונות בינסטל ",
            "loggo" => "./assets/pages/costumers/halls-events/benistal/loggo/benistal.jpg",
            "discription" => 'הנהלתו החדשה של יצחק פדידה,מציעים לכם שלושה אולמות 
            מפוארים הקרויים בשמות אבני החושן,המתאימים לכם לכל אירוע בכל סדר גודל.
            אולם ספיר-עד 180 איש.
            אולם ברקת-עד 280 איש.
            אולם יהלום-עד 600 איש.
            ',
            "email" => "benistal23@gmil.com",
            "tel" => "08-8522668, 08822669",
            "address" => "אשדוד",
            "deals" => " "
        ]);
       Costumer::create([

            "user_id" =>  3,
        	"company" =>  "גולדן",
            "businessType" => "halls events",
            "contact" =>  "הנהלה",
            "title" =>  "גולדן קראון",
            "loggo" =>  "./assets/pages/costumers/halls-events/golden/loggo/golden.jpg",
            "discription" =>  'ולדן קראון. מרכז ארועים. 3 אולמות מפוארים עומדים לרשותכם. חתונות,
            בר מצווה, בריתות, כנסים עד 900 איש. אולם מיוחד לחינות בסגנון מרוקאי עד 250 איש. 
            
             מבצע רק לפונים דרך אתיופיה אירועים במחירים אטרקטיביים
            במיוחד בימיים הקרובים בדקו אותנו.
            
            אתיופיה אירועים לא רק מדברים עושים
            ',
            "email" =>  "margalit3312@wall.com",
            "tel" =>  "08-8566070, 057-3109340",
            "address" => "אשדוד,רחוב האורגים-16",
            "deals" =>  ""
        ]);
       Costumer::create([

            "user_id" =>  4,
	        "company" => "סילבר",
            "businessType" => "halls events",
	        "contact" => "הנהלה",
	        "title" => "סילבר אירועים ",
	        "loggo" => "./assets/pages/costumers/halls-events/silver/loggo/silver.jpg",
	        "discription" => 'סילבר...אולם אירועים ששודרג זה עתה לפי מיטב הקידמה והטכנולוגיה,בכירי
	         האדריכלים השקיעו מאמצים רבים על מנת להפוך את המקום למקום חוותי.מתחם קבלת הפנים תוכנן
	         ועוצב להעניק לראשוני האורחים חווית אירוח ברוח חדשה.התאורה ,המטעמים,הרהיטים 
	        והצוות המקצועי כל אלה נבחרו בקפידה בכדי לחמם את תחילת האירוע ולהזרימו לאולם המרכזי.
	        "פשוט עוצר נשימה" silver
	        
	         מבצע רק לפונים דרך אתיופיה אירועים במחירים אטרקטיביים
	        במיוחד בימיים קרוביםת בדקו אותנו
	        
	        אתיופיה אירועים לא רק מדברים עושים
	        ',
	        "email" => "fakeEmail@kjk.com",
	        "tel" => "08-6722256 ,077-270789",
	        "address" =>"צומת סילבר ,אשקלון",
	        "deals" => ""
	    ]);
    }
}
