<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Repo\CustomersRepo;
use App\Customer;
use App\Gallery;
use App\User;
use App\Http\Requests\UpdateCustomersRequest;
use Validator;

class CustomersController extends Controller
{
    protected  $messages = [
        "errors" => [],
        'success' => []
    ];
    protected $customers;
    protected $formRoles = [
            "company" => "required|min:3",
            "businessType" => "required|min:3",
            "title" => "required|min:4",
            "contact" => "required|min:3",
            "email" => "required|email|unique:users,email|unique:customers,email",
            "tel" => "required|numeric|min:8",
            "address" => "required|min:4",
            "discription" => "required|min:12",
            "deals" => "required|min:6"
        ];
	protected  $convetedMasseges = [
           "company" => "שם חברה",
           "businessType" => "סוג העסק",
           "title" => "כותרת",
           "contact" => "איש קשר",
           "email" => "אימייל",
           "tel" => "פלאפון או טלפון",
           "address" => "כתובת",
           "discription" => "אודות",
           "deals" => "מבצעים"
        ];
        protected $filesRules = [
            'gallery' => "required|file|between:20,4000|image|mimes:png,jpg,bmp",
        	'video' => "required|file|between:200,4000|mimetypes:video/mp4,video/mp4,video/avi,video/mpeg,video/quicktime"
        ];//png, video/mp4,

        protected $conFilesAttr = [
                "loggo" => [
                    "name" => "לוגו",
                    "type" => "תמונה",
                    "ruls" => "required|string|min:2|max:90",//|not_regex:/^[a-z]{2,}/ [\d\/\-\<\>\(\)@]+
                    "minMaxFiles" => [
                        "min" => 1,
                        "max" => 1
                    ]
                    
                ],
                "video" => [
                    "name" => "וידאו",
                    "type" => "וידאו",
                    "ruls" => "required|string|min:2|max:90",//|not_regex:/^[a-z]{2,}/ [\d\/\-\<\>\(\)@]+
                    "minMaxFiles" => [
                        "min" => 1,
                        "max" => 1
                    ]
                    
                ],
                "gallery" => [
                    "name" => "גלריה",
                    "type" => "תמונה",
                    "ruls" => "required|string|min:2|max:90",//|not_regex:/^[a-z]{2,}/ [\d\/\-\<\>\(\)@]+
                    "minMaxFiles" => [
                        "min" => 3,
                        "max" => 12
                    ]
                    
                ]
            ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomersRepo $coRepo)
    {
        // $this->middleware('cors');
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        $this->middleware('auth:api', ['only' => ['store','update', 'destroy']]);
        $this->customers = $coRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$cost = $this->customers->getCustomers();

        return response()->json($cost,200);
    }


    public function show($id)
    {
    	
    	$cost = Customer::where('id', $id)->first();
       return response()->json($cost,200);
    }

    public function store(Request $request){

        if(! Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        /****** grab and decode exissted objects *******/
        $files = $request->file('files');
        $files = isset($files) && ! empty($files)? $files: false;

        

        /****** decode objects *******/
        $formInputs = $request->only('formInputs');
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($request['formInputs'], true): false;

        /**** send message errors if rquires params not exisst ****/
        if(! $files || ! $formInputs){
            $msg = "missing required parameters.";
            return response()->json(['errors' => $msg, $formInputs, $files],200);
        }


        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs(collect($formInputs)->except('loggo')):null;
        $afterValFiles = (isset($files) && count($files))? $this->validatefiles($files, $formInputs): null;


        if((! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValFiles && ! is_null($afterValFiles))){
            // return $this->messages;
            //return response()->json(collect($this->messages)->except('success'),200);
            return response()->json($this->customers->getMessages(),200);
        }


        /***** handel form input before store into database *****/
        
        /***** check and return errors if any  *****/

        $customersDetails = $this->customers->handelDetails($formInputs);

        if(isset($customersDetails["errors"])){ 
            return response()->json(collect($customersDetails['errors'])->only('inputs'),200);
        }
        
        $renemedFiles = $this->customers->getFilesParams($files);

        /***** download files before store into database *****/
        /***** check and return errors if any before store into database *****/
        
        $download = $this->customers->downloadFiles($renemedFiles['files'],$true = true);
        
        
        // return ['items' => $download, "formCallback" => $renemedFiles];

        $downloaded = collect($download)->only('downloaded');
        $filesTsave['image'] = json_encode($downloaded['downloaded']['gallery']);
        $filesTsave['video'] = json_encode($downloaded['downloaded']['video']);

        /***** store form inputs customer into database *****/
        if($filesTsave['image'] && $filesTsave['video'] && ! isset($download['errors'])){


            Customer::create($customersDetails);
            $this->messages['customer'] = $customersDetails;
            sleep(1);
            $customer_id = Customer::where('email',$customersDetails['email'])->first()->id;
            $filesTsave['customer_id'] = $customer_id;
            
            /***** store form filse customer into database *****/
            Gallery::create($filesTsave);
        }else{
            $message = ['files' => ['unexceptedErr' => "unexcepted server error."]];
            // array_push($this->messages['errors'], $message);
            $this->customers->setMessages('errors', $key, $message);
        } 
        

        /******* get and send back messages ******/
        $msgs = [];

        $haveMessagesErrors = (isset($download['errors']) && count($download['errors'])) ? true: false;
        $haveMessagesSuccess =  (isset($download['success'])  && count($download['success'])) ? true: false;

        if($haveMessagesErrors && $haveMessagesSuccess){
            $msgs = ['errors' => $download['errors'], 'success' => $download['success']];
        }else if($haveMessagesErrors){
            $msgs['errors'] = $download['errors'];
        }else if($haveMessagesSuccess){
            $msgs['success'] = $download['success'];
        }
        

        /*if($msgs && count($msgs)){
            
            return response()->json($this->responseMessages($msgs),200);
        } */
        return response()->json($this->customers->getMessages(),200);
    }

    public function update(Request $request, $id){
        
        /****** declare all variables *******/
        $reqMethod = request()->isMethod('patch') || request()->isMethod('put');
        $customer = Customer::find($id);
        $gals = json_decode($customer->gallery['image']);
        $user = auth()->user();
        $same = ($customer->user_id === $user->id)? true:false;
        if(! $same) return response()->json(['error' => 'Unauthorized'], 401);

        /****** grab and decode exissted objects *******/
        $filesToUdate = $request->file('files');
        $filesToUdate = isset($filesToUdate) && ! empty($filesToUdate)? $this->customers->getFilesParams($filesToUdate): [];
        // $renemedFiles = $this->customers->getFilesParams($filesToUdate);
        
        $filesTodelete = $request->only('filesToDelete');
        $filesTodelete = isset($filesTodelete) && ! empty($filesTodelete)? json_decode($request['filesToDelete'], true): [];

        /****** decode objects *******/
        $formInputs = $request->only('formInputs');
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($request['formInputs'], true): [];
        // return ["hhhjj" => $filesTodelete];
        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs($formInputs):null;
        $afterValFiles = (1 > 2) && (isset($filesToUdate) && count($filesToUdate))? $this->validatefiles($filesToUdate): null;
        $afterValDelFiles = $this->mainValidation($filesToUdate, $filesTodelete, $customer);

        
        
        if(!$afterValDelFiles || (! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValFiles && ! is_null($afterValFiles))){
            // return $this->messages;
        	// return response()->json(collect($this->messages)->except('success'),200);
            return response()->json($this->customers->getMessages(),200);
        }

        // return ["hhhjj" => $filesToUdate];
        /***** delete and update files *****/
    	$UpFiles = ($filesToUdate || $filesTodelete)? $this->customers->updateFiles($filesToUdate, $customer, $filesTodelete): [];
        
        
        $formInputs = collect($formInputs)->except('company')->toArray();
        $inputsIsValidated = (isset($formInputs) && count($formInputs));

        /***** if we have input emeil we need to ensure to sync email of user too *****/
        if($inputsIsValidated && isset($formInputs['email'])){
        	$userEmailTeken = User::where('email', $formInputs['email'])->first();

        	if(empty($userEmailTeken)){

        		//$customer->user->update(['email' => $formInputs['email']]);
        	}else{
    			return response()->json(['errors' => [ "email"=> array("האימייל כבר קיים במערכת שלנו.")]],200);
        	}
        }
        //if($inputsIsValidated) $customer->update($formInputs);
        
        /*if(isset($UpFiles['errors']) && count($UpFiles)){
            
            return response()->json($this->responseMessages($UpFiles),200);
        } */
        return response()->json($this->customers->getMessages(),200);
    }

    /******* custom validation *******/

    #rquest is store validation state
        # have only data to store

    # request is update validation state
        # have to update and to delete
        # have to update but no to delete
        # have to delete but no to update
        # have no update and delete

    # validations attributes

    # minFiles
        # min files allowed
    # maxFiles
        # max files allowed
    # min-max filesCahrLen
        # chack min Or max files names length allowed
    # charTypes
        # disallow char types of file name
    # unknownError
        # catch errors and return unexcepted errors
    # fixErrors
        # stop excution until fix errors
    # fileSize
        # file too big Or total files sizes is too big
    # filesUnChange
        # return files requeted unchanged
    # filesExisst
        # file exisst in diroctry or database

    /**** validate min-maxFile example when request is update ****/

    # have to update and to delete
        # have to updat more than allowed
        # have to delete more than allowed
        # have to update and to delete more than allowed
    # have to update but no to delete
        # have to updat more than allowed
    # have to delete but no to update
        # have to delete more than allowed
    # have no update and delete

    private function mainValidation(array $upFiles = [], array $toDelFiles = [], object $customer){

        $filesSize = 0;
        $valFils = [];
        $valFilsNames = [];
        $isValid = true;

        $gals = $customer->gallery;
        $galFiles = json_decode($gals['image'],true);
        $video = json_decode($gals['video'],true);
        $loggo = $customer->loggo;

        $upFilesKeys = $upFiles && count($upFiles)? array_keys($upFiles):false;
        $delFilesKeys = $toDelFiles && count($toDelFiles)? array_keys($toDelFiles):false;

        $item = $this->maper($upFiles, 'getValidationProps');
        
        if($item && count($item)){
            $item = $item[0];
            (isset($item['valFils']['items']))? $valFils['items'] = $item['valFils']['items']: "";
            (isset($item['valFils']['ruls']))? $valFils['ruls'] = $item['valFils']['ruls']: "";

            (isset($item['valFilsNames']['items']))? $valFilsNames['items'] = $item['valFilsNames']['items']: "";
            (isset($item['valFilsNames']['ruls']))? $valFilsNames['ruls'] = $item['valFilsNames']['ruls']: "";

            (isset($item['fileSize']))? $filesSize = $filesSize + $item['fileSize']: "";
        }

        # validate required ,fileSize ,fileType ,mimeType
        $fileIsValid = count($valFils)? $this->validateItems($valFils['items'], $valFils['ruls']):"";
        
        # validate filesMinMaxCharLen
        $filesCharLenIsValid = count($valFilsNames)? $this->validateItems($valFilsNames['items'], $valFilsNames['ruls']): "";

        # validate files to delete exisst
            # files to deleted must be exist in dir and db
            # files uploaded must no exist in dir and db
        $map['del'] = $this->maper($toDelFiles, 'isExist');
        $map['up'] = $this->maper($upFiles, 'isExist');

        $statusDel = array_column($map['del'], 'status');
        $statusUp = array_column($map['up'], 'status');

        # before production need change status variable conditions to not oparator;
        $existStatus = in_array(false, $statusDel) && in_array(true, $statusUp)? false: true;


        /**** validate min-maxFile ****/
        $minMaxfiles = $this->validateMinMaxFiles($upFiles, $toDelFiles, $galFiles);
        $statusMinMax = array_column($minMaxfiles, 'status');
        $minMaxStatus = in_array(false, $statusMinMax)? false: true;

        $this->customers->setMessages('errors', "minMaxF", $minMaxfiles);

        /**** validate total filesSizes ****/
        if($sizeEx = $filesSize? $this->sizeGratherThen($filesSize): 0){
            $message = array('fileSize' => 'נפח הקבצים גדול מידי : ' . $sizeEx);
            $this->customers->setMessages('errors', 'files', $message);
            $isValid = false;
        }

        (! $fileIsValid || ! $filesCharLenIsValid || ! $existStatus || ! $minMaxStatus)? $isValid = false: '';

        return $isValid;
    }

    protected function isExist($target, $items, $exisstedFiles){
        $arr = ['status' => true];

        foreach ($items as $key => $value) {
            $item = isset($value['fullPath'])? $value['fullPath']: $value;
            $file = isset($value['file'])? $value['file']: false;

            $itemDir = (string) $item;
            if(strpos($item, './assets/pages/customers/') !== false){
                $itemDir = (string) explode('./assets/pages/customers/', $item)[1];
            }
            if(! isset($arr[$target])) $arr[$target] = [];

            $dir = $this->customers->fileExist($itemDir, $file);
            $db = in_array($item, $exisstedFiles);
            $arr['status'] = (! $dir || ! $db)? false: '';
            $arr[$target][$item] = ['db' => $db, 'dir' => $dir];
            // array_push($arr[$target], [$item => ['db' => $db, 'dir' => $dir]]);
        }
        return $arr;
    }

    protected function maper(array $files = [], $fn, array $galFiles = []){

        $filesKeys = $files && count($files)? array_keys($files):false;
        $maperOb = $filesKeys? array_map(function($attrs) use($files, $fn, $galFiles){

            $items = isset($files[$attrs]) && count($files[$attrs])? $this->$fn($attrs, $files[$attrs], $galFiles): false;

            return $items;

        }, $filesKeys): [];

        if($arr = count(array_keys($maperOb, false))){
            $maperOb = array_splice($maperOb, $arr);
        }

        if($maperOb && count($maperOb) && ! empty($maperOb)) return $maperOb;
    }

    protected function getValidationProps(string $target, array $items = [], $galFiles){

        $data = [
            'fileSize' => 0,
            'valFils' => [
                'items' => [],
                'ruls' => []
            ],
            'valFilsNames' => [
                'items' => [],
                'ruls' => []
            ]
        ];

        foreach ($items as $key => $value) {

            $file = (object) $value['file'];
            $target = (string) $value['target'];
            $fullPath =  (string) $value['fullPath'];

            $item[$fullPath] = $file;
            $FilesRuls[$fullPath] = ($target == "loggo")? $this->filesRules['gallery']: $this->filesRules[$target];

            $itemName[$fullPath] = $fullPath;
            $FilesNameRuls[$fullPath] = $this->conFilesAttr[$target]['ruls'];
            
            $data['valFils']['items'][$fullPath] = $file;
            $data['valFils']['ruls'][$fullPath] = ($target == "loggo")? $this->filesRules['gallery']: $this->filesRules[$target];

            $data['valFilsNames']['items'][$fullPath] = $fullPath;
            $data['valFilsNames']['ruls'][$fullPath] = $this->conFilesAttr[$target]['ruls'];

            $data['fileSize'] = $data['fileSize'] + filesize($file);
            /*if(is_callable($cBFn)){

                if($delimiter && $delimiter && ($key + 1) == $delimiter) return $cBFn($value);
                $cBFn($value);
            }*/
        }
        return $data;
    }

    private function validateMinMaxFiles(array $upFiles = [], array $toDelFiles = [], array $galFiles = []){

        $isValid = true;
        $validationsItems = ['gallery', 'video', 'loggo'];

        $delFIlesMap = array_map(function($target) use($upFiles, $toDelFiles, $galFiles){
            $cUpfiles = isset($upFiles[$target])? $upFiles[$target]: [];
            $cDelFiles = isset($toDelFiles[$target])? $toDelFiles[$target]: [];

            $maxFiles = $this->minMaxFiles($target, $cUpfiles, $cDelFiles, $galFiles);

            return $maxFiles;//['valStatus' => $maxFiles, $target => ['upFile' => $cUpfiles, 'delFile' => $cDelFiles]];

        }, $validationsItems);
        return $delFIlesMap;
    }

    protected function minMaxFiles($target, array $upFiles = [], array $delFiles = [], array $galFiles = []){

        $isLoggoOrVideo = (($target == "loggo") || ($target == "video"))? true:false;
        $convertedMsg = false;
        $isValid = true;
        $countedUpfiles = count($upFiles);
        $countedDelfiles = count($delFiles);
        $msgs = ['status' => true];

        if($isLoggoOrVideo){
            /*** min ***/
            if(($countedUpfiles && ! $countedDelfiles) || (! $countedUpfiles && $countedDelfiles) || ($countedDelfiles > 1)){
                $convertedMsg = $this->conFilesAttr[$target]["name"] . ": חייב להכיל קובץ " .$this->conFilesAttr[$target]["type"] . " " .$this->conFilesAttr[$target]['minMaxFiles']["min"] . " לפחות";
                $isValid = false;
            }else if(($countedUpfiles > 1)){
                $convertedMsg = $this->conFilesAttr[$target]["name"] . ": יכול להכיל " . $this->conFilesAttr[$target]['minMaxFiles']["max"]." קבצי ".$this->conFilesAttr[$target]["type"]  . " בלבד.";
                $isValid = false;
            }
        }else if($target == "gallery"){

             $totalItemsGal = ((count($galFiles) + $countedUpfiles) - $countedDelfiles);
            if($totalItemsGal < 3){
                
                $convertedMsg = $this->conFilesAttr[$target]["name"] . ": חייב להכיל קובץ " .$this->conFilesAttr[$target]["type"] . " " .$this->conFilesAttr[$target]['minMaxFiles']["min"] . " לפחות";
                $isValid = false;
            }else if($totalItemsGal > 12){
                
                $convertedMsg = $this->conFilesAttr[$target]["name"] . ": יכול להכיל " . $this->conFilesAttr[$target]['minMaxFiles']["max"]." קבצי ".$this->conFilesAttr[$target]["type"]  . " בלבד.";
                $isValid = false;
            }
        }
        $status = $countedUpfiles && $countedDelfiles && $isValid? true: false;
        $msgs[$target] = ! $status? 'no_data': $isValid;
        $msgs['status'] = $status;

        ! $isValid? $this->customers->setMessages('errors', $target, $convertedMsg): "";
        return $msgs;
    }

    private function responseMessages($msgs){
        $messagesTosend = [];
        $messagesTosend['errors'] = isset($msgs['errors']) && count($msgs['errors'])? $this->whileMsgs($this->messages['errors'], $msgs['errors']): $this->messages['errors'];
        $messagesTosend['success'] = isset($msgs['success']) && count($msgs['success'])? $this->whileMsgs($this->messages['success'], $msgs['success']): $this->messages['success'];
        return $messagesTosend;
    }

    private function whileMsgs($firstMsgs, $secondMsgs){

    	$msgsArr = [
    		$firstMsgs,
    		$secondMsgs
    	];
    	$msgsReturn = [];

    	if(empty($firstMsgs)) return $secondMsgs;
    	
    	while ($msgsCurrent = current($msgsArr)) {

    		foreach ($msgsCurrent as $key => $value) {
    			
    			if(count($msgsCurrent[$key])){

    				(isset($msgsReturn[$key]) && count($msgsReturn[$key]))? array_push($msgsReturn[$key], $msgsCurrent[$key]): $msgsReturn[$key] = $msgsCurrent[$key];
    			}
    		}
    		next($msgsArr);
    	}

    	return $msgsReturn;
    }

    public function destroy(Request $request, $id){
    	return $request;
    	$imgs = Customer::find($id)->gallery->image;
    	$imgs = json_decode($imgs, true);
    	$fd = json_decode($request['filesToDelete'], true);

    	$delFiles = $this->customers->delFromGal($imgs, $fd);
        return ['success' => $delFiles['success']];

    }

    private function validInputs($inputes){

    	$myRequest = [];
        $newValRole = [];
        $massegeSuccess = [];
       
    	foreach($inputes as $key => $value) {

            if(! isset($this->formRoles[$key])) {
                return response()->json(['errors' => $key . " dos not exisst in our system"],200);
            }

            $myRequest[$key] = $value;
            $newValRole[$key] = $this->formRoles[$key];
            $massegeSuccess[$key] = $this->convetedMasseges[$key] . " עודכן בהצלחה.";
            // if(! isset($this->messages["success"][$key])) $this->messages["success"][$key] = [];
            $message = [$key => $massegeSuccess[$key]];
            // array_push($this->messages["success"][$key], $message);

            $this->customers->setMessages('success', $key, $message);
        }
    	
        $val = $this->validateItems($myRequest, $newValRole);
        
        // array_push($this->masseges["success"], $massegeSuccess);
        return $val;
    }

    
     private function validateItems($reqItems, $ruls){

    	$valFiles = Validator::make($reqItems, $ruls,[
    		'mimetypes' => ":attribute " . " קובץ וידאו זה לא נתמך",
    		'mimes' => ":attribute " . " קובץ תמונה זה לא נתמך"
    	]);
    	
        if($valFiles->fails()) {
            $msgs = $this->prityMessges($valFiles->errors()->all());
            return false;
        }
        return true;
    }

    public function getTarget($path, $target){
        return (strpos($path , $target) !== false)? $target: false;
    }

    private function prityMessges($messages){


		foreach ($messages as $key => $value) {
		
    		$targetLoggo = $this->getTarget($value, 'loggo')? 'loggo': false;
            $targetVideo = $this->getTarget($value, 'video')? 'video':false;
            $targetGallery =  $this->getTarget($value, 'gallery')? 'gallery':false;


            if($targetLoggo){
                $fNameAndMsg = explode($targetLoggo.'/', $value)[1];

                $fName = explode(' ', $fNameAndMsg)[0];
                $msg = explode($fName, $fNameAndMsg)[1];

                $message = [$targetLoggo => $fName . $msg];

                $this->customers->setMessages('errors', $targetLoggo, $message);
            }

            if($targetVideo){
                $fNameAndMsg = explode($targetVideo.'/', $value)[1];

                $fName = explode(' ', $fNameAndMsg)[0];
                $msg = explode($fName, $fNameAndMsg)[1];

                $message = [$targetVideo => $fName . $msg];

                $this->customers->setMessages('errors', $targetVideo, $message);
            }

            if($targetGallery){
                $fNameAndMsg = explode($targetGallery.'/', $value)[1];
                // $fNameAndMsg = trim($fNameAndMsg);
                $fName = explode(' ', $fNameAndMsg)[0];
                $msg = explode($fName, $fNameAndMsg)[1];

                $message = [$targetGallery => $fName . $msg];

                $this->customers->setMessages('errors', $targetGallery, $message);
            }
            /*
            return;
    		$inputsTarget = (strpos($value , 'target=') !== false)? explode('target=', $value)[1]:false;

            $this->customers->setMessages('errors', $key, $value);

    		if($target){

    			$target = explode('/', $value)[2];
    			$fNname = explode($target.'/', $value)[1];
    			
    		}else if($inputsTarget){
    			
    			$target = explode(' ', $inputsTarget)[0];
    			$fNname = explode($target.' ', $value)[1];
    			if(isset($this->messages['success'][$target])) unset($this->messages['success'][$target]);
    		}else{
    			$target = "שגיאה";
    			$fNname = " שגיאה בלתי צפוייה בדוק ערכים שנשלחו";
    		}

    		$message = [$target => $fNname];
			// if(! isset($this->messages["errors"][$target])) $this->messages["errors"][$target] = [];
			// array_push($this->messages["errors"][$target],$message);
            $this->customers->setMessages('errors', $key, $message);*/
    	}
	}
	
    	

	private function sizeGratherThen($size)
	{
        
	    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	    $e = floor(log($size, 1024));

	    $roundedeSize = round($size/pow(1024, $e), 1, PHP_ROUND_HALF_EVEN);
	    $pow = pow(1024, 2) * 6;
        $isBig =  $size > $pow;
        $this->customers->setMessages('success', 'TESTsize', ['pow' => $pow, 'size' => $size, 'size/pow(1024, mathLog)' => $roundedeSize, 'mathLog' => log($size, 1024)]);
	    return $isBig? $roundedeSize . $s[$e]:false;
	}

}
