<?php

namespace App\Repo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Customer;
use App\Gallery;
use App\User;
use Validator;


/**
 * 
 */
class CustomersRepo 
{
	// private $customer;
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
    protected $dataUrl = "./assets/pages/customers/";

	function __construct(/*Customer $customer*/)
	{
		# code...
		//$this->customer = $customer;
	}

	public function getCustomers(){
		$cost['customers'] = $this->filterCustomers(Customer::all());
        //$cost['galleries'] = $this->filterGalleries(Gallery::all());
        return $cost;
	}

	private function filterCustomers($customers){
        $filteredCustomers = [];
        $businessType = null;
        // return count($customers);
        foreach ($customers as $value) {
            $fixCbt = explode(" ", $value->businessType);
            $fixCcn = explode(" ", $value->company);

            $fixCbt = isset($fixCbt[1])? $fixCbt[0] . '-' . $fixCbt[1]:$value->businessType;
            $fixCcn = isset($fixCcn[1])? $fixCcn[0] . '-' . $fixCcn[1]:$value->company;

            if((isset($businessType) && $businessType != $fixCbt) || is_null($businessType)){
                $businessType = $fixCbt;
                $filteredCustomers[$fixCbt] = [];
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
            
            
            array_push($filteredCustomers[$businessType], $en);
        }
        return $filteredCustomers;
    }

    //responsable for get key value of customers before stor database
    public function handelDetails($inputs){

        $user = User::where('email',$inputs['email'])->first();
    	$compName = Customer::where('company',$inputs['company'])->first();
        $hasErrors = false;
    	$autUser = auth()->user();
    	$userisCustomer = $autUser->customer;
        // return  $userisCustomer;
    	if(! $compName && ! $userisCustomer && isset($user) && $autUser->email === $inputs['email']){
    	
    	    $inputs['user_id'] = $autUser->id;
    	    $inputs['loggo'] = $this->dataUrl . $inputs['loggo'];
    	    $inputs['deals'] =  "מבצעים בהמשך.";

    	}else{
    	    array_push($this->masseges["errors"]['inputs'],  ["auth-error" => "somthing went wrong with your request."]);
            $hasErrors = true;
    	}
		return ($hasErrors)? $this->masseges:$inputs;
    }

    protected function getUrlParms($linkName, $file){

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
                    'gallery' => [],
                    'loggo' => [],
                    'video' => []
                ];
              
            /*$key = key($file);
            $value = $file[$key];*/
        foreach ($files as $key => $value) {
            # code...
            $fileNameParams = $this->getUrlParms($key, $value);
            $fileExists = $this->fileExist($fileNameParams['fullName'],$value);

            if($fileExists){
                array_push($this->masseges["errors"][$fileNameParams["target"]],  [$fileNameParams['target'] => "הקובץ כבר קיים במערכת " .$fileNameParams['name']]);
                continue;
            }


            if($fileNameParams["target"] === "gallery"){
                array_push($downloadedFiles['gallery'], ($nameFlag)? $fileNameParams["fullUrl"]: $fileNameParams["fullName"]);//($nameFlag)?
                array_push($this->masseges['success']['gallery'], ['gallery' => "הקובץ עודכן בהצלחה " . $fileNameParams["name"]]);
            }
            
               
            if($fileNameParams["target"] === "loggo"){
                array_push($downloadedFiles['loggo'], ($nameFlag)? $fileNameParams["fullUrl"]: $fileNameParams["fullName"]);//($nameFlag)?
                array_push($this->masseges['success']['loggo'], ['loggo' => "הקובץ עודכן בהצלחה " . $fileNameParams["name"]]);
            }
            
            if($fileNameParams["target"] === "video"){
                array_push($downloadedFiles['video'], ($nameFlag)? $fileNameParams["fullUrl"]: $fileNameParams["fullName"]);//($nameFlag)?
                array_push($this->masseges['success']['video'], ['video' => "הקובץ עודכן בהצלחה " . $fileNameParams["name"]]);
            } 

            //Storage::disk('arc')->putFileAs('customers', new File($value), $fileName);
            //Storage::disk('arc')->put('/sysfiles/', $value);
            // Storage::putFileAs('/public/', new File($value), $fileName["fullName"]);
            
        }
        
        $this->masseges['downloaded'] = $downloadedFiles;

        return $this->masseges;
    }

    public function findDelFile(array $items){

        foreach ($items as $key => $value) {
            # code...
            if($items[$key]) return $value;
        }
        return false;
    }

    public function updateFiles($files, $customer, $filesToDelete = false){
        
        $gals = $customer->gallery;
        $imgs = json_decode($gals['image'],true);
        $video = json_decode($gals['video'],true);
        $loggo = $customer->loggo;
       
        $fDelete = $filesToDelete? $filesToDelete:false;

        $downloadedFiles = [
                    'gallery' => [],
                    'loggo' => [],
                    'video' => []
                ];
        $num = 1;

        foreach ($files as $keyFile => $fileObj) {

            $reqName = $fileObj->getClientOriginalName();
            $exPlode = explode(':', $reqName);
            $target = $exPlode[0];

            $fileName = $exPlode[count($exPlode) - 1];
            $path = $this->getFullPath($exPlode);
            $fullUrl = $this->dataUrl . $path .'.'. ($fileObj)->extension();


            $fileNameEx = $fileName.'.'. ($fileObj)->extension();
            $fullName = $this->dataUrl . $path;

            $fileExists = $this->fileExist($fullName,$fileObj);
            
            /*   *********  file to delete procces   **********   */
            $fdTarget = $fDelete && isset($fDelete[$target]) && count($fDelete[$target])? $fDelete[$target]:false;

            $loggoDelFileName = ($target == "loggo" && $fdTarget)? explode($target .'/',$fdTarget[0])[1]:false;
            $videoDelFileName =($target == "video" && $fdTarget)? explode($target .'/',$fdTarget[0])[1]:false;

            /*$galleryDelFile =($target == "gallery" && $fdTarget)? 
                        array_filter($fdTarget, function($value, $key) use($imgs, $target){
                
                        return in_array($value, $imgs);
            },ARRAY_FILTER_USE_BOTH): false;*/

            $findDel = ($target == "gallery" && $fdTarget && isset($fdTarget[0]) && in_array($fdTarget[0], $imgs))? $fdTarget[0]: false;

            $galleryDelFileName = $findDel? explode($target .'/',  $findDel)[1]: false;

            
            if($target == 'loggo' && ! $fileExists){
                
                $dl = $this->downloadFiles([$path => $fileObj]);
                $downloaded = $dl['downloaded'];
                // array_push($this->masseges['success'][$target], [$target => $fName]);
                $item = isset($downloaded["loggo"]) && count($downloaded["loggo"])? $downloaded["loggo"]: false;
                if(! $item) { continue;}

                //$customer->loggo = $item;
                //$gals->save();
                //Storage::delete($loggo);

                array_push($downloadedFiles['loggo'], $item);
                unset($files[$keyFile]);

                $fdTarget = array_diff($fdTarget,array($loggo));
                $fDelete[$target] = $fdTarget;

                $deleted = ['deletedFiles' => $loggoDelFileName];
                // array_push($this->masseges['success']['loggo'], $item);
                array_push($this->masseges['success']['loggo'], $deleted);
            }else if($target == 'video' && ! $fileExists){//&& $fdTarget

                $dl = $this->downloadFiles([$path => $fileObj]);
                $downloaded = $dl['downloaded'];

                $item = isset($downloaded["video"]) && count($downloaded["video"])? $downloaded["video"]: false;
                if(! $item) { continue;}

                //$gals->video = json_encode($item);
                //$gals->save();
                //Storage::delete($video);
                array_push($downloadedFiles['video'], $item);
                unset($files[$keyFile]);

                $fdTarget = array_diff($fDelete[$target],$video);
                $fDelete[$target] = $fdTarget;

                $deleted = ['deletedFiles' => $videoDelFileName];
                // array_push($this->masseges['success']['video'], $item);
                array_push($this->masseges['success']['video'], $deleted);
            }else if($target == 'gallery'  && ! $fileExists){// && $fdTarget


                $dl = $this->downloadFiles([$path => $fileObj], true);
                $downloaded = $dl['downloaded'];
                
                $item = isset($downloaded["gallery"]) && count($downloaded["gallery"])? $downloaded["gallery"][0]: false;
                if(! $item) continue;
                
                array_push($downloadedFiles['gallery'], $item);

                if($fdTarget && $findDel){
                    
                    array_push($this->masseges['success']['gallery'], ['deletedFiles' => $galleryDelFileName]);
                    $fdTarget = array_diff($fdTarget,[$findDel]);
                    $fDelete[$target] = $fdTarget;
                }
                
                unset($files[$keyFile]);
                // $files = array_diff($files,array($keyFile));
                //array_push($this->masseges['success']['gallery'], $galItem);
            }
        }
        
        
        $df =  $fDelete && isset($fDelete['gallery'])? count($fDelete['gallery']):false;
        $count = count($imgs) > 2;
        
        if($count && $df){
            
            $dlFiles = $this->delFromGal($imgs, $fDelete['gallery'], count($downloadedFiles['gallery']));
            $imgs = array_merge($dlFiles,$downloadedFiles['gallery']);
            //$gals['gallery'] = json_encode($imgs);
            //$gals->save();

        } 
        
        return $this->masseges;
    }


    public function delFromGal($imgs, $fDelete, $downloadedFiles = false){
        $fd = $fDelete;
        $galImgs = $imgs;
        // return [];
        foreach ($galImgs as $key => $img) {
                
            $fileName = explode('gallery/', $img)[1];
            $fileExists = $this->fileExist($img);
            $inarr = in_array($img, $fd);
            $imgCount = (int) $downloadedFiles? ((int) $downloadedFiles + count($galImgs)) > 2: count($galImgs) > 3;
            // array_push($this->masseges['success']['gallery'], ['couint' => $img]);

            if($inarr && $imgCount){
                //Storage::delete($img);
                $fd = array_diff($fd,array($img));
                unset($galImgs[$key]);
                array_push($this->masseges['success']['gallery'], ['deletedFiles' => $fileName]);
            }else{
                // break;
                //array_push($this->masseges['errors']['gallery'], ["gallery" => "error ocurs with the file name ". $fileName . " in_array ". $inarr ." imgcount ". count($galImgs)]);
            }
        }

        return $galImgs;
    }

    function getFullPath($paths){
        $path;
        foreach ($paths as $key => $value) {
            if($key === 0) continue;
            (! isset($path))? $path = $value: $path = $path.'/'.$value;
        }
        return $path;
    }

    protected function strContaines($h, $n){
        return (strpos($h, $n) !== false)? true:false;
    }

    protected function fileExist($h, $n = false){
        
        $r =  $n? Storage::disk('public')->exists($h) && $n->isValid(): Storage::disk('public')->exists($h);
        return $r;
    }
}
