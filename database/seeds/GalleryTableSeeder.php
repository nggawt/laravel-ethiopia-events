<?php

use Illuminate\Database\Seeder;
use App\Costumer;
use App\Gallery;

class GalleryTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $media = [
            ["./assets/pages/costumers/" => "/galleries/palace1.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace2.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace3.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace4.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace5.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace6.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace7.jpg"],
            ["./assets/pages/costumers/" => "/galleries/palace8.jpg"],
            ["./assets/pages/costumers/" => "/video/km.mp4"]
        ];

        */
        $costumers = Costumer::all();
       
        foreach ($costumers as $costumer) { 
                
            $fixCbt = explode(" ", $costumer->businessType);
            $fixCcn = explode(" ", $costumer->company);

            $fixCbt = isset($fixCbt[1])? $fixCbt[0] . '-' . $fixCbt[1]:$costumer->businessType;
            $fixCcn = isset($fixCcn[1])? $fixCcn[0] . '-' . $fixCcn[1]:$costumer->company;
            switch ($fixCcn) {

                case 'ארמונות-לב':
                    # code...
                $fixCcn = "palace-lev";
                    break;
                case 'בניסטל':
                    # code..../assets/pages/costumers/halls_events/benistal/loggo/benistal.jpg
                $fixCcn = "benistal";
                    break;
                case 'סילבר':
                    # code...
                $fixCcn = "silver";
                    break;
                case 'גולדן':
                    # code...
                $fixCcn = "golden";
                    break;
                
                default:
                    # code...

                    break;
            }
            /* new gallary*/
            

            $img = [
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace1.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace2.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace3.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace4.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace5.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace6.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace7.jpg",
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/gallery/palace8.jpg"

            ];
            $vid = [
                "./assets/pages/costumers/" .$fixCbt."/".$fixCcn."/video/km.mp4"
            ];

            $gal = [
                "costumer_id" => $costumer->id,
                "image" => json_encode($img),
                "video" => json_encode($vid)
            ];

            Gallery::create($gal);

        }
        
    }
}
