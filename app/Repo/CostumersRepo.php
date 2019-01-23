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
    protected  $messages = [
                /*'errors' => [
                    'gallery' => [],
                    'loggo' => [],
                    'video' => [],
                    'inputs' => []
                ],
                'success' => [
                    'gallery' => [],
                    'loggo' => [],
                    'video' => []
                ]*/
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
    	    $messege = ["Auth-error" => "somthing went wrong with your request."];
            $this->setMessages('errors', 'Auth-error', $messege);
            $hasErrors = true;
    	}
		return ($hasErrors)? $this->messages:$inputs;
    }

    public function setMessages($type, $target, $message){

        if(! isset($this->messages[$type])) $this->messages[$type] = [];
        if(! isset($this->messages[$type][$target])) $this->messages[$type][$target] = [];

        array_push($this->messages[$type][$target], $message);
    }

    public function getMessages(){
        return $this->messages;
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

    private function looper($files, $cBFn, $delimiter = false, $delFiles){
        $arr = ['downloaded' => [], 'deleted' => []];
        // return count($files[$cBFn]);
        foreach ($files as $key => $value) {

            $target = $value["target"];
            $down = $this->updateFilesTEst($value, $delFiles);

            
            $this->setMessages('success', 'TESTrepo181', $delFiles);

            isset($down['downFiles']) && count($down['downFiles'])? array_push($arr['downloaded'], $down['downFiles'][0]):"";
            // $fdTarget = isset($delFiles[0])? array_diff($delFiles,[$delFiles[0]]): [];
            // $delFiles = $fdTarget;
            array_shift($delFiles);
            isset($down['delFiles']) && count($down['delFiles'])? array_push($arr['deleted'], $down['delFiles'][0]):"";

            if(is_callable($cBFn)){

                if($delimiter && ($target == $delimiter || $delimiter == ($key + 1))) return $cBFn($arr);
                $cBFn($value);
            }

        }
       return $arr;
    }

    protected function updateFilesTEst($fileItem, $fDelete){

        $target = $fileItem['target'];
        $downloaded = [];
        $fdTarget = [];
        $fdl = isset($fDelete[0]) && count($fDelete)? $fDelete[0]: false;

        $exDelFileName = $fdl? explode('/', $fdl): false;
        $delFileName = $exDelFileName? $exDelFileName[count($exDelFileName) - 1]: false;

        if($target == 'loggo'){
        
            $dl = $this->downloadFiles([$fileItem], true);
            $downloaded = $dl['downloaded'][$target];
            $item = isset($downloaded["loggo"]) && count($downloaded["loggo"])? $downloaded["loggo"]: false;

            //$customer->loggo = $item;
            //$gals->save();
            //Storage::delete($loggo);

            //$fdTarget = array_diff($fdl, [$fdl[0]]);
            // $fdl[$target] = $fdTarget;

            $messege = ['deletedFiles' => $delFileName];
            $this->setMessages('success', $target, $messege);

        }else if($target == 'video'){//&& $fdTarget


            $dl = $this->downloadFiles([$fileItem], true);
            $downloaded = $dl['downloaded'][$target];

            $item = isset($downloaded["video"]) && count($downloaded["video"])? $downloaded["video"]: false;

            //$gals->video = json_encode($item);
            //$gals->save();
            //Storage::delete($video);

            //$fdTarget = array_diff($fdl,[$fdl[0]]);
            // $fdl[$target] = $fdTarget;

            $messege = ['deletedFiles' => $delFileName];
            $this->setMessages('success', $target, $messege);
            
        }else if($target == 'gallery'){// && $fdTarget

            $dl = $this->downloadFiles([$fileItem], true);
            $downloaded = $dl['downloaded'][$target];

            if($fdl){

                //Storage::delete($fdl[0];
                $messege = ['deletedFiles' => $delFileName];
                $this->setMessages('success', $target, $messege);
            }

            return [
                'downFiles' => $downloaded && count($downloaded)? $downloaded: [],
                'delFiles' => $fdl?[$fdl]: []
            ];

        }
    }

    public function downloadFiles($files, $nameFlag = false){

        $downloadedFiles = [
                    /*'gallery' => [],
                    'loggo' => [],
                    'video' => []*/
                ];

        foreach ($files as $key => $value) {
            
            $file = $value['file'];
            $target = $value["target"];
            $name = $value["name"];
            $fullPath =  $value['fullPath']. '.' . $value['ext'];
            $fullUrl = $this->dataUrl . $fullPath;

            $messege = [$target => "הקובץ עודכן בהצלחה " . $name];
            $this->setMessages('success', $target, $messege);
            (! isset($downloadedFiles[$target]))? $downloadedFiles[$target] = [($nameFlag)? $fullUrl: $name]: array_push($downloadedFiles[$target], ($nameFlag)? $fullUrl: $name);
            //Storage::disk('arc')->putFileAs('customers', new File($value), $fileName);
            //Storage::disk('arc')->put('/sysfiles/', $value);
            // Storage::putFileAs('/public/', new File($file), $fullPath);
        }
        
        $this->messages['downloaded'] = $downloadedFiles;

        return $this->messages;
    }

    public function updateFiles($files, $customer, $filesToDelete = false){
        
        $gals = $customer->gallery;
        $imgs = json_decode($gals['image'],true);
        $video = json_decode($gals['video'],true);
        $loggo = $customer->loggo;
       

        $proccFiles = [
                    'downloaded' => [],
                    'deleted' => []
                ];

        foreach ($files as $key => $value) {

            $fDelete = ($filesToDelete && count($filesToDelete[$key]))? $filesToDelete[$key]:[];
            $items = $this->looper($files[$key], false, false, $fDelete);

            $colFiles = collect($items)->collapse();
            // $this->setMessages('success', 'TESTrepo311', $items);

            // $proccFiles['downloaded'] = $items['downloaded'];
            // $proccFiles['deleted'] = $items['deleted'];
            if(isset($items['downloaded']) && count($items['downloaded'])) $proccFiles['downloaded'] = $items['downloaded'];
            if(isset($items['deleted']) && count($items['deleted'])) $proccFiles['deleted'] = $items['deleted'];
        }
        $this->setMessages('success', 'TESTrepo318', $proccFiles);
        $this->setMessages('success', 'TESTrepo319', $filesToDelete);
        
        $galleryDelFiles = $filesToDelete && isset($filesToDelete['gallery']) && count($filesToDelete['gallery'])? array_diff($filesToDelete['gallery'], $proccFiles['deleted']): false;

        $df =  $fDelete && isset($fDelete['gallery'])? count($fDelete['gallery']):false;
        $count = count($imgs) > 2;
        
        if($count && $galleryDelFiles){
            
            $dlFiles = $this->delFromGal($imgs, $fDelete['gallery'], count($proccFiles['downloaded']));
            $imgs = array_merge($dlFiles,$proccFiles['downloaded']);
            //$gals['gallery'] = json_encode($imgs);
            //$gals->save();

        } 
        
        return $this->messages;
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
            // array_push($this->messages['success']['gallery'], ['couint' => $img]);

            if($inarr && $imgCount){
                //Storage::delete($img);
                
                $messege = ['deletedFiles' => $fileName];
                $this->setMessages('success', 'gallery', $messege);

                $fd = array_diff($fd,array($img));
                unset($galImgs[$key]);
            }else{
                // break;
                //array_push($this->messages['errors']['gallery'], ["gallery" => "error ocurs with the file name ". $fileName . " in_array ". $inarr ." imgcount ". count($galImgs)]);
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

    public function strContaines($h, $n){
        return (strpos($h, $n) !== false)? true:false;
    }

    public function fileExist($link, $file = false){
        
        $result =  $file? Storage::disk('public')->exists($link) && $file->isValid(): Storage::disk('public')->exists($link);
        return $result;
    }
}
