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
use Carbon\Carbon;


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
            $imgs = $value->gallery->images;
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
                    'content' => $value->content,
                    'address' => $value->address,
                    'tel' => $value->tel,
                    'deals' => $value->deals,
                    'created_at' => Carbon::parse($value->created_at)->format('Y-m-d H:i:s')
                ],

                "gallery" => [
                    'image' => $imgs? (gettype(json_decode($imgs)) == "object")? $this->objectToArray(json_decode($imgs)): json_decode($imgs):[],
                    'video' => $vids? json_decode($vids):[]
                ],
                "events" => $evt
            ];
            array_push($filteredCustomers[$businessType], $en);
        }
        return $filteredCustomers;
    }

    protected function objectToArray($items){
        $arr = [];
        foreach ($items as $key => $value) {
            # code...
            array_push($arr, $value);
        }
        return $arr;
    }

    public function getFilesParams($files, $callBack = false, $delimiter = false){
        

        $media = [];
        $allowedAttribute = ['images', 'video', 'loggo'];

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

    public function looper($target, $files, $fn){
        
        $fnResponse = [];
        foreach ($files as $key => $value) {

            $item = $this->$fn($target, $value, true)[$target]; 
            if($item){
                if(! isset($fnResponse[$target])) $fnResponse[$target] = [];
                array_push($fnResponse[$target], $item);
            }
        }
       return $fnResponse;
    }

    public function downloadFiles($target, $files, $nameFlag = false){

        $downloadedFiles = [];

        $file = $files['file'];
        $target = $files["target"];
        $name = $files["name"];
        $fullPath =  $files['fullPath']. '.' . $files['ext'];
        $fullUrl = $this->dataUrl . $fullPath;

        $url = ($nameFlag)? $fullUrl: $name;


        $down = Storage::putFileAs('customers', new File($file), $fullPath);//
        $messege = [$target => "הקובץ עודכן בהצלחה " . $name ,'status' => $down];
        $this->setMessages('success', $target, $messege);
        
        $downloadedFiles[$target] = $down? $url: $down;
        return $downloadedFiles;
    }

    protected function deleteFromStorage($target, string $link = ''){
        $deletedItems = [];
        $posTarget = (strpos($link , $target.'/') === false)? "gallery/": $target.'/';

        $fileName = explode($posTarget , $link)[1];
        
        $linkToFile = explode('customers/', $link)[1];

        $deleted = Storage::disk('customers')->delete($linkToFile);

        $messege = ['deletedFiles' => $fileName, "status" => $deleted];
        $this->setMessages('success', $target, $messege);
        
        $deletedItems[$target] = $deleted? $link: $deleted;//$deleted
        return $deletedItems;
    }

    public function updateFiles($items, $deles, $customer){
        
        $proccesed = [];
        $itemsKeys = [];
        $galImages = json_decode($customer->gallery->image, true);
        $galvideo = json_decode($customer->gallery->video, true);
        // foreach ($items as $key => $value) {
        //     $proccesed[$key] = $this->looper($key, $value, $fn)[$key];
        // }
        $collectionDown = collect($items);
        $collectionDeles = collect($deles);

        $itemsKeys['down']  = $items? $collectionDown->map(function($value, $itemKeys){
            $downLoadResault = $this->looper($itemKeys, $value, 'downloadFiles');
            if($downLoadResault && $downLoadResault[$itemKeys]) return $downLoadResault[$itemKeys];
        })->toArray(): false;

        $itemsKeys['del']  = $deles? $collectionDeles->map(function($value, $itemKeys){
            $deletedResault = $this->looper($itemKeys, $value, 'deleteFromStorage');
            if($deletedResault && $deletedResault[$itemKeys]) return $deletedResault[$itemKeys];
        })->toArray(): false;

        $resaults = [];

        if($itemsKeys['down'] && count($itemsKeys['down'])){
            $downLoaded = $itemsKeys['down'];
            $images = (isset($downLoaded['images']) && count($downLoaded['images']))? $downLoaded['images']: false;
            $video = (isset($downLoaded['video']) && count($downLoaded['video']))? $downLoaded['video']: false;
            $loggo = (isset($downLoaded['loggo']) && count($downLoaded['loggo']))? $downLoaded['loggo']: false;
            
            if($images){
                $resultImages = array_merge($galImages, $images);
                $resaults['image'] = $resultImages;
            }

            if($video){
                $resaults['video'] = $video;
            }

            if($loggo){
                $resaults['loggo'] = $loggo;
            }
        }

        if($itemsKeys['del'] && count($itemsKeys['del'])){

            $deleted = $itemsKeys['del'];

            $images = (isset($deleted['images']) && count($deleted['images']))? $deleted['images']: false;
            $video = (isset($deleted['video']) && count($deleted['video']))? $deleted['video']: false;
            $loggo = (isset($deleted['loggo']) && count($deleted['loggo']))? $deleted['loggo']: false;
            
            if($images){
                $result =  (isset($resaults['image']) && count($resaults['image']))? array_diff($resaults['image'], $images): 
                array_diff($galImages, $images);
                $resaults['image'] = $result;

                $messege = ['deletedFiles' => $images, "result" => $result, 'original' => $galImages];
                $this->setMessages('success', 'images', $messege);
            }

            if($video){
                $result =  (isset($resaults['video']) && count($resaults['video']))? array_diff($resaults['video'], $video): $video;
                $resaults['video'] = $result;
            }

            if($loggo){
                $result =  (isset($resaults['loggo']) && count($resaults['loggo']))? 
                array_diff($resaults['loggo'], $loggo): $loggo;
                $resaults['loggo'] = $result;
            }
        }

        return $resaults;
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
            $imgs = array_diff($imgs, array($link));
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
        
        $result =  $file? (Storage::disk('customers')->exists($link) && $file->isValid()): Storage::disk('customers')->exists($link);
        return $result;
    }
}
