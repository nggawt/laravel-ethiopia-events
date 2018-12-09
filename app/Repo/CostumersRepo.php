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
    protected  $masseges = [
                'errors' => [
                    'gallery' => [],
                    'loggo' => [],
                    'video' => [],
                    'inputs' => []
                ],
                'success' => [
                    'gallery' => [],
                    'loggo' => [],
                    'video' => []
                ]
    ];
    protected $dataUrl = "./assets/pages/costumers/";

	function __construct(/*Costumer $costumer*/)
	{
		# code...
		//$this->costumer = $costumer;
	}

	public function getCostumers(){
		$cost['costumers'] = $this->filterCostumers(Costumer::all());
        //$cost['galleries'] = $this->filterGalleries(Gallery::all());
        return $cost;
	}

	private function filterCostumers($costumers){
        $filteredCostumers = [];
        $businessType = null;
        // return count($costumers);
        foreach ($costumers as $value) {
            $fixCbt = explode(" ", $value->businessType);
            $fixCcn = explode(" ", $value->company);

            $fixCbt = isset($fixCbt[1])? $fixCbt[0] . '-' . $fixCbt[1]:$value->businessType;
            $fixCcn = isset($fixCcn[1])? $fixCcn[0] . '-' . $fixCcn[1]:$value->company;

            if((isset($businessType) && $businessType != $fixCbt) || is_null($businessType)){
                $businessType = $fixCbt;
                $filteredCostumers[$fixCbt] = [];
            }
            $imgs = $value->gallery->image;
            $vids = $value->gallery->video;
            $en = [
                "customer" => [
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
                ],

                "gallery" => [
                    'image' => $imgs?json_decode($imgs):null,
                    'video' => $vids?json_decode($vids):null
                ]
            ];
            
            
            array_push($filteredCostumers[$businessType], $en);
        }
        return $filteredCostumers;
    }

    //responsable for get key value of customers before stor database
    public function handelDetails($inputs){

        $user = User::where('email',$inputs['email'])->first();
    	$compName = Costumer::where('company',$inputs['company'])->first();
        $hasErrors = false;
    	$autUser = auth()->user();
    	$userisCostumer = $autUser->costumer;
        // return  $userisCostumer;
    	if(! $compName && ! $userisCostumer && isset($user) && $autUser->email === $inputs['email']){
    	
    	    $inputs['user_id'] = $autUser->id;
    	    $inputs['loggo'] = $this->dataUrl . $inputs['loggo'];
    	    $inputs['deals'] =  "מבצעים בהמשך.";

    	}else{
    	    array_push($this->masseges["errors"]['inputs'],  ["auth-error" => "sonthing went wrong with your dtails."]);
            $hasErrors = true;
    	}
		return ($hasErrors)? $this->masseges:$inputs;
    }

    protected function extractFileName($linkName, $file){

        $exploded = explode('/', $linkName);
        $ext = ($file)->extension();
        $name = $exploded[3]. '.'.$ext;
        $target = $exploded[2];
        $fullName = $linkName.'.'. $ext;

        return [
            "target" => $target,
            "fullName" => $fullName,
            'fullUrl' => $this->dataUrl . $fullName,
            "name" => $name,
            "ext" => $ext
        ];
        
    }
    public function downloadFiles($files, $nameFlag = false){

        $downloadedFiles = [
                    'image' => [],
                    'loggo' => [],
                    'video' => []
                ];
                //return ["msg" => $files];
        $hasErrors = false;
        
        /*$filesExists = array_filter($files,function($v,$k){
            
            $fileName = $this->extractFileName($k, $v);
            $fileExists = $this->fileExist($fileName['fullName'],$v);
            
            if($fileExists){
                array_push($this->masseges["errors"][$fileName["target"]],  [$fileName['target'] => "הקובץ כבר קיים במערכת " .$fileName['name']]);
            }

            return $fileExists;
            
        },ARRAY_FILTER_USE_BOTH);


        if(! $filesExists){*/

            foreach($files as $key => $value){
                
                $fileName = $this->extractFileName($key, $value);
                $fileExists = $this->fileExist($fileName['fullName'],$value);

                if($fileExists){
                    array_push($this->masseges["errors"][$fileName["target"]],  [$fileName['target'] => "הקובץ כבר קיים במערכת " .$fileName['name']]);
                    continue;
                }

                // if($target === "galleries") array_push($downloadedFiles['image'], ['gallery' => $fileName]);
                // if($target === "loggo") array_push($downloadedFiles['loggo'], ['loggo' => $fileName]);
                // if($target === "video") array_push($downloadedFiles['video'], ['video' => $fileName]);

                //return Storage::putFileAs('/public/', new File($value), $fileName);
                

                if($fileName["target"] === "gallery"){
                    array_push($downloadedFiles['image'], ($nameFlag)? $fileName["fullUrl"]: $fileName["fullName"]);//($nameFlag)?
                    array_push($this->masseges['success']['gallery'], ['gallery' => "הקובץ עודכן בהצלחה " . $fileName["name"]]);
                }
                
                   
                if($fileName["target"] === "loggo"){
                    array_push($downloadedFiles['loggo'], ($nameFlag)? $fileName["fullUrl"]: $fileName["fullName"]);//($nameFlag)?
                    array_push($this->masseges['success']['loggo'], ['loggo' => "הקובץ עודכן בהצלחה " . $fileName["name"]]);
                }
                
                if($fileName["target"] === "video"){
                    array_push($downloadedFiles['video'], ($nameFlag)? $fileName["fullUrl"]: $fileName["fullName"]);//($nameFlag)?
                    array_push($this->masseges['success']['video'], ['video' => "הקובץ עודכן בהצלחה " . $fileName["name"]]);
                } 

                //Storage::disk('arc')->putFileAs('costumers', new File($value), $fileName);
                //Storage::disk('arc')->put('/sysfiles/', $value);
                Storage::putFileAs('/public/', new File($value), $fileName["fullName"]);
            // }

        }
        // return ["myMsg" => $hasErrors, "errors" => $this->masseges["errors"]];
        // $masseges = ! $hasErrors?  $this->masseges['success'] = :$downloadedFiles;
        $this->masseges['downloaded'] = $downloadedFiles;

        // return $this->masseges;
        return $this->masseges;//$hasErrors? $this->masseges: $msgSuccess;
    }

    public function updateFiles($files, $customer, $filesToDelete = false){
        
        $gals = $customer->gallery;
        $imgs = json_decode($gals['image'],true);
        $video = json_decode($gals['video'],true);
        $loggo = $customer->loggo;

        $fDelete = $filesToDelete? $filesToDelete:false;

        $downloadedFiles = [
                    'image' => [],
                    'loggo' => [],
                    'video' => []
                ];
                    
        foreach ($files as $keyFile => $fileObj) {
            # code...
            
            $fileNameEx = $keyFile.'.'. ($fileObj)->extension();
            $fileName = $fileNameEx;
            $fullName = $this->dataUrl . $fileNameEx;
            $fileExists = $this->fileExist($fullName,$fileObj);
            
            if($this->strContaines($keyFile, 'loggo') && $fDelete){
                
                $fn = explode('loggo/',  $loggo)[1];
                $df = in_array($loggo, $fDelete);
        
                if($df && ! $fileExists){

                    $dl = $this->downloadFiles([$keyFile => $fileObj]);
                    $item = isset($dl["errors"]) && count($dl["errors"])? false: $dl['loggo'][0];
                    if(! $item) {
                        // return ["loggo" => "loggo didnt passed the test"];
                        //return ["loggo" => $this->masseges["errors"]];

                        //array_push($this->masseges["errors"] = ["loggo" => $dl["errors"][0]]);
                        //array_push($this->masseges['errors'], ["loggo" => "error ocurs with loggo file"]);
                        continue;
                    }

                    array_push($downloadedFiles['loggo'], $item);
                    unset($files[$keyFile]);

                    $deleted = ['deletedFiles' => $fn];
                    $fDelete = array_diff($fDelete,array($loggo));
                    array_push($this->masseges['success']['loggo'], $item);
                    array_push($this->masseges['success']['loggo'], $deleted);
                }
            }
            if($this->strContaines($keyFile, 'video') && $fDelete){
                $fn = explode('video/',  $video[0])[1];
                $df = in_array($video[0], $fDelete);
                
                if($df && ! $fileExists){
                    $dl = $this->downloadFiles([$keyFile => $fileObj]);
                    $item = isset($dl["errors"]) && count($dl["errors"])? false: $dl['video'][0];
                    if(! $item) {
                        // array_push($this->masseges["errors"], ["video" => $dl["errors"][0]]);
                        // array_push($this->masseges['errors'], ["video" => "error ocurs with the file"]);
                        continue;
                    }
                    // if($item) $gals->video = json_encode($item);
                    //$gals->save();
                    //Storage::delete($video);
                    array_push($downloadedFiles['video'], $item);
                    unset($files[$keyFile]);
                    $fDelete = array_diff($fDelete,$video);

                    $deleted = ['deletedFiles' => $fn];
                    array_push($this->masseges['success']['video'], $item);
                    array_push($this->masseges['success']['video'], $deleted);
                    //return $this->masseges['success']['video'][0];
                }
            }
            // return $files;
            if($this->strContaines($keyFile, 'gallery')){

                if(! $fileExists){
                    $dl = $this->downloadFiles([$keyFile => $fileObj]);
                    // return $dl;
                    $galItem = isset($dl["errors"]) && count($dl["errors"])? false: $dl['gallery'][0];
                    if(! $galItem) {
                        // array_push($this->masseges["errors"], ["gallery" => $dl]);
                        // array_push($this->masseges['errors']["gallery"], ["gallery" => "error ocurs with the file"]);
                        continue;
                    }
                    array_push($downloadedFiles['image'], $galItem);
                    unset($files[$keyFile]);
                    //array_push($this->masseges['success']['gallery'], $galItem);
                }
            }
        }
        //return $filesToDelete;
        $df =  $fDelete? count($fDelete):false;
        $count = count($imgs) > 2;
        
        if($count && $df && empty($this->masseges["errors"])){
            
            $dlFiles = $this->delFromGal($imgs, $fDelete, $downloadedFiles);

            $imgs = array_merge($dlFiles,$downloadedFiles['image']);
            //$gals['image'] = json_encode($imgs);
            //$gals->save();

        } 
        
        return $this->masseges;//empty($this->masseges["errors"])? $this->masseges['success']: $this->masseges;
    }


    public function delFromGal($imgs, $fDelete, $downloadedFiles = false){

        foreach ($imgs as $key => $img) {
                
            $fileName = explode('gallery/', $img)[1];
            $fileExists = $this->fileExist($img);
            $inarr = in_array($img, $fDelete);
            $imgCount = $downloadedFiles? (count($downloadedFiles['image']) + count($imgs)) > 2: count($imgs) > 3;

            if($inarr && $imgCount){
                //Storage::delete($img);
                $fDelete = array_diff($fDelete,array($img));
                unset($imgs[$key]);
                array_push($this->masseges['success']['gallery'], ['deletedFiles' => $fileName]);
            }else{
                //array_push($this->masseges['errors'], ["gallery" => "error ocurs with the file name ". $fileName]);
            }
        }
        return $downloadedFiles? $imgs: ['remain' => $imgs, 'success' => $this->masseges['success']];
    }
    protected function strContaines($h, $n){
        return (strpos($h, $n) !== false)? true:false;
    }

    protected function fileExist($h, $n = false){
        
        $r =  $n? Storage::disk('public')->exists($h) && $n->isValid(): Storage::disk('public')->exists($h);
        return $r;
    }
}
