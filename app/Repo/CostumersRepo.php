<?php

namespace App\Repo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Costumer;
use App\Gallery;
use App\User;
use Validator;


/**
 * 
 */
class CostumersRepo 
{
	// private $costumer;
    protected  $masseges = [];
    protected $dataUrl = "./assets/pages/costumers/";

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
    public function handelDetails($inputs){

        $user = User::where('email',$inputs['email'])->first();
    	$compName = Costumer::where('company',$inputs['company'])->first();

    	$autUser = auth()->user();
    	$userisCostumer = $autUser->costumer;

    	if(! $compName && ! $userisCostumer && isset($user) && $autUser->email === $inputs['email']){
    	
    	    $inputs['user_id'] = $autUser->id;
    	    $inputs['loggo'] = $this->dataUrl . $inputs['loggo'];
    	    $inputs['tel'] = $inputs['tel'];
    	    $inputs['deals'] =  "מבצעים בהמשך.";

    	}else{
    	    array_push($this->masseges,  ["errors" => ["msg" => "sonthing went wrong with your dtails."]]);
    	}
		return count($this->masseges)? $this->masseges:$inputs;
    }

    public function downloadFiles($files){
        
        $img = [];
        $vid = [];

        $filesExists = array_filter($files,function($v,$k){
            
            $fileName = $k.'.'. ($v)->extension();
            $fileExists = Storage::disk('public')->exists($fileName) && $v->isValid();
            if($fileExists) array_push($this->masseges["errors"], [$key => "The File already exists"]);
            return $fileExists;
        },ARRAY_FILTER_USE_BOTH);

        if(! $filesExists){

            foreach($files as $key => $value){
                $target = explode('/', $key)[2];
                $fileName = $key.'.'. ($value)->extension();

                if($target === "video") array_push($vid, $this->dataUrl . $fileName);
                if($target === "galleries") array_push($img, $this->dataUrl . $fileName);
                Storage::putFileAs('public/', new File($value), $fileName);
            }
        }
        
        return empty($this->masseges)?["image" => json_encode($img),"video" => json_encode($vid)]: $this->masseges;
    }
}
