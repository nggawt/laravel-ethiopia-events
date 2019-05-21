<?php

namespace App\Repo;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Customer;
use App\Gallery;
use App\User;
use App\Repo\traits\Messages;
use Validator;


/**
 * 
 */
class CustomersRepo 
{
	// private $customer;
    
    use Messages;

    protected $dataUrl = "./assets/pages/customers/";

	function __construct(/*Customer $customer*/)
	{
		# code...
		//$this->customer = $customer;
	}

	public function getCustomers(){
		$cost['customers'] = $this->filterCustomers(Customer::all());
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
            $evt = $value->user->events;
            $en = [
                "customer" => [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'confirmed' => $value->confirmed,
                    'company' => $fixCcn,
                    'businessType' => $fixCbt,
                    'title' => $value->title,
                    'contact' => $value->contact,
                    'loggo' => $value->loggo,
                    'email' => $value->email,
                    'descriptions' => $value->descriptions,
                    'address' => $value->address,
                    'tel' => $value->tel,
                    'deals' => $value->deals
                ],

                "gallery" => [
                    'image' => $imgs? json_decode($imgs):[],
                    'video' => $vids? json_decode($vids):[]
                ],
                "events" => $evt
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
    	    $messege = ["Auth-error" => "somthing went wrong with your request."];
            $this->setMessages('errors', 'AuthError', $messege);
            $hasErrors = true;
    	}
		return ($hasErrors)? $this->messages:$inputs;
    }

    public function getFilesParams($files, $callBack = false, $delimiter = false){
        

        $media = [];
        $allowedAttribute = ['gallery', 'video', 'loggo'];

        foreach ($files as $key => $file) {

            $reqName = $files[$key]->getClientOriginalName();
            $exPlode = explode(':', $reqName);
            
            $target = $exPlode[0];
            if(in_array($target, $allowedAttribute)){
                $fileName = $exPlode[count($exPlode) - 1];

                $fullPath = $this->getFullPath($exPlode);
                $ext = ($file)->extension();

                $params = [
                        "target" => $target,
                        "fullPath" => $fullPath,
                        'fullUrl' => $this->dataUrl . $fullPath,
                        "name" => $fileName,
                        "file" => $file,
                        "ext" => $ext
                    ];
                    // is_file(filename)
                if(! isset($media[$target])) $media[$target] = [];
                array_push($media[$target], $params);

                if($callBack && is_callable($callBack)){

                    if(! $delimiter) return $callBack($params);
                    if($delimiter && $delimiter == $target) return $callBack($params);
                }
            }else{
                $media = [];
                $this->setMessages('errors', 'unexpectedError', "wrong parameters.");
                break;
            }
        }
        
        return $delimiter || (count($media) < 1)? false: $media;
    }

    public function looper($files, $fn, $delFiles = [], $istrue = false){
        
        $downloaded = [
            'downloaded' => [],
            'deleted' => []
        ];
        foreach ($files as $key => $value) {

            $target = $value["target"];
            if(! isset($downloaded['downloaded'][$target])) $downloaded['downloaded'][$target] = [];
            if(! isset($downloaded['deleted'][$target])) $downloaded['deleted'][$target] = [];
               
            $fnResponse = $this->$fn($value, $delFiles, $istrue);
            $this->setMessages('errors', "TESTrepo185", $fnResponse);
            $fnTarget = isset($fnResponse[$target])? true:false;

            $resDlTarget = isset($fnResponse['downloaded']) && count($fnResponse['downloaded'])? true:false;
            $resDelTarget = isset($fnResponse['deleted']) && count($fnResponse['deleted'])? true: false;

            $resultTarget = $resDlTarget && isset($fnResponse['downloaded'][$target])? true:false;
            $resultDelTarget = $resDelTarget && isset($fnResponse['deleted'][$target])? true:false;

            ($resultTarget)? array_push($downloaded['downloaded'][$target], $fnResponse['downloaded'][$target]):"";
            $fnTarget? array_push($downloaded['downloaded'][$target], $fnResponse[$target][0]): "";

            ($resultDelTarget)? array_push($downloaded['deleted'][$target], $fnResponse['deleted'][$target]): "";
            ($fnTarget && $resDelTarget)? array_push($downloaded['deleted'][$target], $fnResponse[$target][0]): "";

            $this->setMessages('errors', "TESTrepo200", $downloaded);
            ($delFiles && is_array($delFiles) && count($delFiles))? array_shift($delFiles): '';

        }
       return $downloaded;
    }

    protected function updateFilesTEst($fileItem, $fDelete){

        $target = $fileItem['target'];
        $downloaded = [
            'downloaded' => [],
            'deleted' => []
        ];

        $fdTarget = [];
        $fdl = isset($fDelete[0]) && count($fDelete)? $fDelete[0]: false;
        ($fdl)? $downloaded['deleted'][$target] = $fdl: "";

        $exDelFileName = $fdl? explode('/', $fdl): false;
        $delFileName = $exDelFileName? $exDelFileName[count($exDelFileName) - 1]: false;

        if($target == 'loggo'){
        
            $download = $this->downloadFiles($fileItem, $fdl, true);
            $downloaded['downloaded'][$target] = $download[$target][0];

            //$item = isset($downloaded["loggo"]) && count($downloaded["loggo"])? $downloaded["loggo"]: false;

            //$customer->loggo = $item;
            //$gals->save();
            //Storage::delete($loggo);


            $messege = ['deletedFiles' => $delFileName];
            $this->setMessages('success', $target, $messege);

        }else if($target == 'video'){//&& $fdTarget


            $download = $this->downloadFiles($fileItem, $fdl, true);
            $downloaded['downloaded'][$target] = $download[$target][0];

            //$item = isset($downloaded["video"]) && count($downloaded["video"])? $downloaded["video"]: false;

            //$gals->video = json_encode($item);
            //$gals->save();
            // ($fdl)? Storage::delete($fdl): '';

            $messege = ['deletedFiles' => $delFileName];
            $this->setMessages('success', $target, $messege);
            
        }else if($target == 'gallery'){

            $download = $this->downloadFiles($fileItem, $fdl, true);
            // $this->setMessages('success', "TST2", $download);

            $dled =  (isset($downloaded[$target]) && count($downloaded[$target]));

            $downloaded['downloaded'][$target] = $download[$target][0];

            if($fdl){

                //Storage::delete($fdl[0];
                $messege = ['deletedFiles' => $delFileName];
                $this->setMessages('success', $target, $messege);
            }
        }
        return $downloaded;
    }

    public function downloadFiles($files, $toDel, $nameFlag = false){

        $downloadedFiles = [
                    /*'gallery' => [],
                    'loggo' => [],
                    'video' => []*/
                ];

        // foreach ($files as $key => $value) {
            
            $file = $files['file'];
            $target = $files["target"];
            $name = $files["name"];
            $fullPath =  $files['fullPath']. '.' . $files['ext'];
            $fullUrl = $this->dataUrl . $fullPath;

            $messege = [$target => "הקובץ עודכן בהצלחה " . $name];
            $url = ($nameFlag)? $fullUrl: $name;

            // $dlTarget = isset($downloadedFiles[$target]) && count($downloadedFiles[$target]);

            // (! $dlTarget)? $downloadedFiles[$target] = [$url]: array_push($downloadedFiles[$target], $url);

            $this->setMessages('success', $target, $messege);

            Storage::putFileAs('customers', new File($file), $fullPath);

            (isset($toDel) && (is_array($toDel) || is_object($toDel)) && count($toDel))? Storage::disk('customers')->delete(explode('customers/', $toDel)[1]): '';

            // $msg = [$fullPath => explode('customers/', $toDel)[1]];
            // $this->setMessages('success', 'video', $msg);
            
            //Storage::disk('arc')->putFileAs('customers', new File($files), $fileName);
            //Storage::disk('arc')->put('/sysfiles/', $files);
            // Storage::putFileAs('/public/', new File($file), $fullPath);
        // }
        $downloadedFiles[$target] = [$url];
        return $downloadedFiles;
    }

    public function updateFiles($files, $customer, $filesToDelete = false){
        
        $gals = $customer->gallery;
        $imgs = json_decode($gals['image'],true);
        $video = json_decode($gals['video'],true);
        $loggo = $customer->loggo;
        $downloadedFiles = [
            'image' => [],
            'video' => [],
            'loggo' => []
        ];

        foreach ($files as $key => $value) {

            $fDelete = ($filesToDelete && count($filesToDelete[$key]))? $filesToDelete[$key]:[];
            $items = $this->looper($files[$key], 'updateFilesTEst', $fDelete);
            // return $key;
            if(isset($items['downloaded'][$key]) && count($items['downloaded'][$key])){
                if(isset($items['downloaded']["gallery"])) $imgs = array_merge($imgs, $items['downloaded'][$key]);
                (! isset($downloadedFiles[$key]))? $downloadedFiles[$key] = [] : '';
                array_push($downloadedFiles[$key], $items['downloaded'][$key][0]);
            }

            if(isset($items['deleted'][$key]) && count($items['deleted'][$key])){
                $imgs = array_diff($imgs, $items['deleted'][$key]);
                $filesToDelete[$key] = array_diff($fDelete, $items['deleted'][$key]);
            }
        }

        $deletedFilesExist = $filesToDelete && isset($filesToDelete['gallery']) && count($filesToDelete['gallery']);
        
        if($deletedFilesExist){
            $dl = $this->delFromGal($filesToDelete['gallery'], $imgs);
            $this->setMessages('success', 'TESTrepo320', ['toDelete' => $deletedFilesExist, 'imgs' => $dl]);
        }
        $downloadedFiles['image'] = $imgs;
        return $downloadedFiles;
    }


    public function delFromGal($fDelete, $imgs, $downloadedFiles = false){
        
        foreach ($fDelete as $key => $link) {
            
            # delete file
            //Storage::delete($img);
            # procces and send message succfulle
            $fileName = explode('gallery/', $link)[1];

            $messege = ['deletedFiles' => $fileName];
            $this->setMessages('success', 'gallery', $messege);

            # extract left item wthin gll imgs
            $imgs = array_diff($imgs,array($link));
        }

        return $imgs;
    }

    function getFullPath($paths){
        $path;
        foreach ($paths as $key => $value) {
            if($key === 0) continue;
            (! isset($path))? $path = $value: $path = $path.'/'.$value;
        }
        return $path;
    }

    public function strContaines($h, $n){
        return (strpos($h, $n) !== false)? true:false;
    }

    public function fileExist($link, $file = false){
        
        $result =  $file? Storage::disk('customers')->exists($link) && $file->isValid(): Storage::disk('customers')->exists($link);
        return $result;
    }
}
