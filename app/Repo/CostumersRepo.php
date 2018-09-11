<?php

namespace App\Repo;

use App\Costumer;
use App\Gallery;
use App\User;


/**
 * 
 */
class CostumersRepo 
{
	// private $costumer;
    protected  $masseges = [];

	function __construct(/*Costumer $costumer*/)
	{
		# code...
		//$this->costumer = $costumer;
	}

	public function getCostumers(){
		$cost['costumers'] = $this->filterCostumers(Costumer::all());
        $cost['galleries'] = $this->filterGalleries(Gallery::all());
        // $cost['videos'] = $this->filterVideos(Video::all());

        return $cost;
	}

	private function filterCostumers($costumers){
        $filteredCostumers = [];

        foreach ($costumers as $value) {
            $fixCbt = explode(" ", $value->businessType);
            $fixCcn = explode(" ", $value->company);

            $fixCbt = isset($fixCbt[1])? $fixCbt[0] . '-' . $fixCbt[1]:$value->businessType;
            $fixCcn = isset($fixCcn[1])? $fixCcn[0] . '-' . $fixCcn[1]:$value->company;
            
            $en = [
                'id' => $value->id,
                'user_id' => $value->user_id,
                'company' => $fixCcn,
                'businessType' => $fixCbt,
                'title' => $value->title,
                'contact' => $value->contact,
                'loggo' => $value->loggo,
                'email' => $value->email,
                'discription' => $value->discription,
                'address' => $value->address,
                'tel' => $value->tel,
                'deals' => $value->deals

            ];
            $filteredCostumers[] = $en;
        }
        return $filteredCostumers;
    }

    private function filterGalleries($galleries){

        $filterGallerie = [];
        foreach ($galleries as $gallery) {
            $en = [
                'id' => $gallery->id,
                'costumer_id' => $gallery->costumer_id,
                'image' => json_decode($gallery->image),
                'video' => json_decode($gallery->video)
            ];
            $filterGallerie[] = $en;
        }
        return $filterGallerie;
    }

    //responsable for get key value of customers before stor database
    public function handelDetails($costumers){

       // return response()->json(["massege" => "Repos you already our costumer"],200);
        $dataUrl = "./assets/pages/costumers/";
        $costumerArray = [];

        $inputs = json_decode($costumers['formInputs']);
        $costumerArray = [];

        foreach ($inputs as $input) {
        	
        	$user = User::where('email',$inputs->email)->first();

        	$autUser = auth()->user();
        	$userisCostumer = $autUser->costumer;


        	if(! $userisCostumer && isset($user) && $autUser->email === $inputs->email){
        	
        	    $costumerArray['user_id'] = $autUser->id;
        	    $costumerArray['company'] = $inputs->company;
        	    $costumerArray['businessType'] = $inputs->businessType;
        	    $costumerArray['title'] = $inputs->title;
        	    $costumerArray['loggo'] = $dataUrl . $inputs->loggo;
        	    $costumerArray['contact'] = $inputs->contact;
        	    $costumerArray['discription'] = $inputs->about;
        	    $costumerArray['email'] = $inputs->email;
        	    $costumerArray['tel'] = $inputs->phone;
        	    $costumerArray['address'] = $inputs->address;
        	    $costumerArray['deals'] =  "מבצעים בהמשך.";

        	}
        	else{
        	    $costumerArray["massege"] = ["msg" => "sonthing went wrong with your dtails.","isCostumer" => $userisCostumer?$userisCostumer:false];
        	}
        }
       
		return $costumerArray;
    }

    public function downloadFiles($files){
        $img = [];
        $vid = [];

        foreach($files as $key => $value){

            $target = explode('/', $key)[2];
            $fileName = $key.'.'. ($value)->extension();
           
            if(! Storage::disk('public')->exists($fileName) && $value->isValid()){
                
                if($target === "video") array_push($vid, $this->dataUrl . $fileName);
                if($target === "galleries") array_push($img, $this->dataUrl . $fileName);
                
                Storage::putFileAs('public/', new File($value), $fileName);

            }else if(Storage::disk('public')->exists($fileName)){
                $this->masseges[$key] = "The File  exists in our storage From BUzzi";
            }else{
                $this->masseges["massege"] = "Error occurred while Uploading the File";
            }
        }
        return empty($this->masseges)?[
            "img" => json_encode($img),
            "vid" => json_encode($vid)
        ]: $this->masseges;
    }
}


/*

        $inputs = json_decode($costumers['formInputs']);
        $costumerArray = [];
        foreach ($inputs as $input) {
        	# code...
        	$user = User::where('email',$inputs->email)->first();

        	if(isset($user) && auth()->user()->email === $inputs->email){
        	
        	    $costumerArray['user_id'] = $user->id;
        	    $costumerArray['company'] = $inputs->company;
        	    $costumerArray['businessType'] = $inputs->businessType;
        	    $costumerArray['title'] = $inputs->title;
        	    $costumerArray['loggo'] = $this->dataUrl . $inputs->loggo;
        	    $costumerArray['contact'] = $inputs->contact;
        	    $costumerArray['discription'] = $inputs->about;
        	    $costumerArray['email'] = $inputs->email;
        	    $costumerArray['tel'] = $inputs->phone;
        	    $costumerArray['address'] = $inputs->address;
        	    $costumerArray['deals'] =  "מבצעים בהמשך.";


        	}
        	else{
        	    $costumerArray["massege"] = "sonthing went wrong with your dtails.";
        	}
        }
       
		return $costumerArray;

*/