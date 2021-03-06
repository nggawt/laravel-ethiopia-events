<?php

use Illuminate\Database\Seeder;
use App\Customer;
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
            ["./assets/pages/customers/" => "/galleries/palace1.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace2.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace3.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace4.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace5.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace6.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace7.jpg"],
            ["./assets/pages/customers/" => "/galleries/palace8.jpg"],
            ["./assets/pages/customers/" => "/video/km.mp4"]
        ];

        */

        // DB::table('galleries')->truncate();

        $customers = Customer::all();

        foreach ($customers as $customer) {

            $fixCbt = explode(" ", $customer->businessType);
            $fixCcn = explode(" ", $customer->company);

            $fixCbt = isset($fixCbt[1]) ? $fixCbt[0] . '-' . $fixCbt[1] : $customer->businessType;
            $fixCcn = isset($fixCcn[1]) ? $fixCcn[0] . '-' . $fixCcn[1] : $customer->company;
            switch ($fixCcn) {

                case 'ארמונות-לב':
                    # code...
                    $fixCcn = "palace-lev";
                    break;
                case 'בניסטל':
                    # code..../assets/pages/customers/halls_events/benistal/loggo/benistal.jpg
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
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace1.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace2.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace3.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace4.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace5.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace6.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace7.jpg",
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . "/gallery/palace8.jpg"

            ];
            $vid = [
                "./assets/pages/customers/" . $fixCbt . "/" . $fixCcn . ($fixCcn == "palace-lev" ? "/video/lady-g.mp4" : "/video/km.mp4")
            ];

            $gal = [
                "customer_id" => $customer->id,
                "images" => json_encode($img),
                "videos" => json_encode($vid),
                "confirmed" => rand(0, 1),
            ];

            Gallery::create($gal);
        }
    }
}
