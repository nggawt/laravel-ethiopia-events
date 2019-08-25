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
        $customers = Customer::all();
       
        foreach ($customers as $customer) { 
                
            $businessType = slug_heb($customer->businessType);
            $company = slug_heb($customer->company);

            switch ($company) {

                case 'ארמונות-לב':
                $company = "palace-lev";
                    break;
                case 'בניסטל':
                $company = "benistal";
                    break;
                case 'סילבר':
                $company = "silver";
                    break;
                case 'גולדן':
                $company = "golden";
                    break;
                
                default:
                    break;
            }

            $img = [
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace1.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace2.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace3.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace4.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace5.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace6.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace7.jpg",
                "./assets/pages/customers/" .$businessType."/".$company."/images/palace8.jpg"

            ];
            $vid = [
                "./assets/pages/customers/" .$businessType."/".$company."/video/km.mp4"
            ];

            $gal = [
                "customer_id" => $customer->id,
                "images" => json_encode($img),
                "video" => json_encode($vid)
            ];

            Gallery::create($gal);

        }
        
    }
}
