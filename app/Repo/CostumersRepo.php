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
                    'image' => $imgs?json_decode($imgs):[],
                    'video' => $vids?json_decode($vids):[]
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
    	    array_push($this->masseges["errors"]['inputs'],  ["auth-error" => "somthing went wrong with your request."]);
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
              
        foreach($files as $key => $value){
            
            $fileName = $this->extractFileName($key, $value);
            $fileExists = $this->fileExist($fileName['fullName'],$value);

            if($fileExists){
                array_push($this->masseges["errors"][$fileName["target"]],  [$fileName['target'] => "הקובץ כבר קיים במערכת " .$fileName['name']]);
                continue;
            }


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
            
        }
        
        $this->masseges['downloaded'] = $downloadedFiles;

        return $this->masseges;
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
            // return $files ;
            $fileNameEx = $keyFile.'.'. ($fileObj)->extension();
            $fileName = $fileNameEx;
            $fullName = $this->dataUrl . $fileNameEx;
            $fileExists = $this->fileExist($fullName,$fileObj);
            
            if($this->strContaines($keyFile, 'loggo') && $fDelete){
                
                $fn = explode('loggo/',  $loggo)[1];
                $df = in_array($loggo, $fDelete);
        
                if($df && ! $fileExists){

                    $dl = $this->downloadFiles([$keyFile => $fileObj]);
                    $downloaded = collect($dl)->only('downloaded');

                    $item = isset($downloaded["loggo"]) && count($downloaded["loggo"])? $downloaded["loggo"]: false;
                    if(! $item) { continue;}

                    //$customer->loggo = $item;
                    //$gals->save();
                    //Storage::delete($loggo);

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
                    $downloaded = collect($dl)->only('downloaded');

                    $item = isset($downloaded["video"]) && count($downloaded["video"])? $downloaded["video"]: false;
                    if(! $item) { continue;}

                    //$gals->video = json_encode($item);
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
                    $downloaded = collect($dl)->only('downloaded');

                    $item = isset($downloaded["image"]) && count($downloaded["image"])? $downloaded["image"]: false;
                    if(! $item) { continue;}

                    
                    array_push($downloadedFiles['image'], $item);
                    array_push($this->masseges['success']['gallery'], $galItem);
                    unset($files[$keyFile]);
                    //array_push($this->masseges['success']['gallery'], $galItem);
                }
            }
        }
        //return $filesToDelete;
        $df =  $fDelete? count($fDelete):false;
        $count = count($imgs) > 2;
        
        if($count && $df){
            
            $dlFiles = $this->delFromGal($imgs, $fDelete, count($downloadedFiles['image']));

            $imgs = array_merge($dlFiles,$downloadedFiles['image']);
            //$gals['image'] = json_encode($imgs);
            //$gals->save();

        } 
        
        return $this->masseges;//empty($this->masseges["errors"])? $this->masseges['success']: $this->masseges;
    }


    public function delFromGal($imgs, $fDelete, $downloadedFiles = false){
        $fd = $fDelete;
        $galImgs = $imgs;

        foreach ($galImgs as $key => $img) {
                
            $fileName = explode('gallery/', $img)[1];
            $fileExists = $this->fileExist($img);
            $inarr = in_array($img, $fd);
            $imgCount = (int) $downloadedFiles? ((int) count($downloadedFiles) + count($galImgs)) > 2: count($galImgs) > 3;

            if($inarr && $imgCount){
                //Storage::delete($img);
                $fd = array_diff($fd,array($img));
                unset($galImgs[$key]);
                array_push($this->masseges['success']['gallery'], ['deletedFiles' => $fileName]);
            }else{
                break;
                //array_push($this->masseges['errors']['gallery'], ["gallery" => "error ocurs with the file name ". $fileName . " in_array ". $inarr ." imgcount ". count($galImgs)]);
            }
        }

        return $galImgs;
    }

    protected function strContaines($h, $n){
        return (strpos($h, $n) !== false)? true:false;
    }

    protected function fileExist($h, $n = false){
        
        $r =  $n? Storage::disk('public')->exists($h) && $n->isValid(): Storage::disk('public')->exists($h);
        return $r;
    }
}
