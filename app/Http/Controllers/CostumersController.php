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
            "company" => "required|string|min:3",
            "businessType" => "required|string|min:3",
            "title" => "required|string|min:4",
            "contact" => "required|string|min:3",
            "email" => "required|string|email|unique:customers,email",
            "tel" => "required|string|numeric|min:8",
            "address" => "required|string|min:4",
            "discription" => "required|string|min:12",
            // "deals" => "required|string|min:6"
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

    protected function mapItems($files, $tar){
        
        $items = $this->customers->looper($files,'downloadFiles', $true = true)['downloaded'];
        return $items;
    }

    public function store(Request $request){

        if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        /****** grab and decode exissted objects *******/
        $files = $request->file('files');
        $files = isset($files) && ! empty($files)? $this->customers->getFilesParams($files): false;
        $method = $request->method();
        // $filesToUdate = $request->file('files');
        // $filesToUdate = isset($filesToUdate) && ! empty($filesToUdate)? $this->customers->getFilesParams($filesToUdate): [];

        /****** decode objects *******/
        $formInputs = $request->only('formInputs');
        // return ['formInputs' => $formInputs, "files" => $files];
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($formInputs['formInputs'], true): false;

        /**** send message errors if rquires params not exisst ****/
        if(! $files || ! $formInputs){
            $badRequest = ['badRequest' => array(["request" => "missing data rquest"])];
            return response()->json(['errors' => $badRequest],200);
        }


        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs(collect($formInputs)->except('loggo'), $method):null;
        // $afterValFiles = (isset($files) && count($files))? $this->mainValidation($files, [], []): null;

        $valItems = ['store'=> $files];
        $afterValFiles = $this->mainValidation($valItems, $method);//$filesToUdate, $filesTodelete, $customer
        
        // return ['afterValInputs' => $afterValInputs, "files" => $files];

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
        

        /***** download files before store into database *****/
        /***** check and return errors if any before store into database *****/
        $filesKeys = array_keys($files);
        $download = array_map([$this, 'mapItems'], $files, $filesKeys);
        
        $downloaded = collect($download)->collapse();
        // return ['download' => $downloaded, "files" => $files];

        $filesTsave['image'] = json_encode($downloaded['gallery']);
        $filesTsave['video'] = json_encode($downloaded['video']);

        /***** store form inputs customer into database *****/
        if($filesTsave['image'] && $filesTsave['video']){


            // Customer::create($customersDetails);
            // $this->messages['customer'] = $customersDetails;
            // sleep(1);
            // $customer_id = Customer::where('email',$customersDetails['email'])->first()->id;
            // $filesTsave['customer_id'] = $customer_id;
            
            /***** store form filse customer into database *****/
            // Gallery::create($filesTsave);
        }else{
            $message = ['files' => ['unexceptedErr' => "unexcepted server error."]];
            // array_push($this->messages['errors'], $message);
            $this->customers->setMessages('errors', "unexcepted", $message);
        } 
        

        /******* get and send back messages ******/
        return response()->json($this->customers->getMessages(),200);
    }

    public function update(Request $request, $id){
        
        if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        /****** declare all variables *******/
        $reqMethod = request()->isMethod('patch') || request()->isMethod('put');
        $customer = Customer::find($id);
        $gals = json_decode($customer->gallery['image']);
        $user = auth()->user();
        $same = ($customer->user_id === $user->id)? true:false;
        if(! $same) return response()->json(['error' => 'Unauthorized'], 401);

        /****** grab and procces existed uploaded files objects *******/
        $filesToUdate = $request->file('files');
        $files = isset($filesToUdate) && ! empty($filesToUdate)? $this->customers->getFilesParams($filesToUdate): [];
        
        /****** decode objects *******/
        $filesTodelete = $request->only('filesToDelete');
        $filesTodelete = isset($filesTodelete) && ! empty($filesTodelete)? json_decode($request['filesToDelete'], true): [];

        $formInputs = $request->only('formInputs');
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($request['formInputs'], true): [];

        /***** if we have no data at all return ******/
        if((! $files || empty($files)) && (! $filesTodelete || empty($filesTodelete)) && (! $formInputs || empty($formInputs))){
            $badRequest = ['badRequest' => array(["request" => "missing data rquest"])];
            return response()->json(['errors' => $badRequest],200);
        }
        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs($formInputs):null;

        $valItems = ['update'=> $files, 'delete' => $filesTodelete, 'customer' => $customer];
        $afterValDelFiles = ($files || $filesTodelete)? $this->mainValidation($valItems, request()->method()): null;

        
        if((! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValDelFiles && ! is_null($afterValDelFiles))){
            
        	// return response()->json(collect($this->messages)->except('success'),200);
            return response()->json($this->customers->getMessages(),200);
        }

        /***** delete and update files *****/
    	$UpFiles = ($files || $filesTodelete)? $this->customers->updateFiles($files, $customer, $filesTodelete): [];
        
        
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
        /******* get and send back messages ******/
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

    private function mainValidation(array $files = [], string $requestMethod){

        $filesSize = 0;
        $valFils = [];
        $valFilsNames = [];
        $isValid = true;
        $galFiles = [];
        $upFiles = [];
        // $upFiles = isset($files['update'])? $files['update']:($requestMethod == "POST" && isset($files['store']))? $files['store']: [];

        if(isset($files['update']) && count($files['update'])){
            $upFiles = $files['update'];

        }else if(isset($files['store']) && count($files['store'])){
            $upFiles = $files['store'];
        }

        $toDelFiles = isset($files['delete'])? $files['delete']: [];
        $customer = isset($files['customer'])? $files['customer']: [];
        
        if($customer){
            $gals = $customer->gallery;
            $galFiles = json_decode($gals['image'],true);
            $video = json_decode($gals['video'],true);
            $loggo = $customer->loggo;
        }
        

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
        $fileIsValid = count($valFils)? $this->validateItems($valFils['items'], $valFils['ruls']): true;
        
        # validate filesMinMaxCharLen
        $filesCharLenIsValid = count($valFilsNames)? $this->validateItems($valFilsNames['items'], $valFilsNames['ruls']): true;

        # validate files to delete exisst
            # files to deleted must be exist in dir and db
            # files uploaded must no exist in dir and db
        $map['del'] = $toDelFiles? $this->maper($toDelFiles, 'isExist', $customer, 'delete'): [];
        $map['up'] = $upFiles? $this->maper($upFiles, 'isExist', $customer, 'uploade'): [];
        $this->customers->setMessages('errors', 'TEST357', $map);

        $statusDel = array_column($map['del'], 'status');
        $statusUp = array_column($map['up'], 'status');

        # before production need change status variable conditions to not oparator;
        $exist = in_array(false, $statusUp) || in_array(false, $statusDel)? false: true;//in_array(false, $statusDel) && 


        /**** validate min-maxFile ****/
        $minMaxfiles = $this->validateMinMaxFiles($upFiles, $toDelFiles, $galFiles);
        $minMaxStatus = $minMaxfiles['status'] && $requestMethod == "POST"? $minMaxfiles['allow_store']: $minMaxfiles['status'];
        (! $minMaxStatus)? $this->customers->setMessages('errors', 'minMaxFile', $minMaxfiles): "";

        /**** validate total filesSizes ****/
        if($sizeEx = $filesSize? $this->sizeGratherThen($filesSize): 0){
            $message = array('fileSize' => 'נפח הקבצים גדול מידי : ' . $sizeEx);
            $this->customers->setMessages('errors', 'files', $message);
            $isValid = false;
        }

        (! $fileIsValid || ! $filesCharLenIsValid || ! $exist || ! $minMaxStatus)? $isValid = false: '';
        $this->customers->setMessages('errors', "TEST379isValid", ['isValid' => $isValid]);
        
        return $isValid;
    }

    protected function isExist($target, $items, $exisstedFiles, string $typeFiles = ""){
        $arr = ['status' => true];

        foreach ($items as $key => $value) {
            $item = isset($value['fullPath'])? $value['fullPath'].".".$value['ext']: $value;
            $file = isset($value['file'])? $value['file']: false;

            $fileName = explode('/', $item)[count(explode('/', $item)) - 1];

            $itemDir = (string) $item;
            if(strpos($item, './assets/pages/customers/') !== false){
                $itemDir = (string) explode('./assets/pages/customers/', $item)[1];
            }
            if(! isset($arr[$target])) $arr[$target] = [];

            $dir = $this->customers->fileExist($itemDir, $file);
            $db = in_array($item, $exisstedFiles);

            //(! $dir || ! $db)? $arr['status'] = false: '';
            $arr[$target][$item] = ['db' => $db, 'dir' => $dir];

            //test
            // $this->customers->setMessages('errors', $typeFiles, [$itemDir => ["dir" => $dir , "db" => $db]]);

            if($typeFiles == 'delete' && (! $db || ! $dir)){ 
                $msgNotExist = ['fileExist' => $fileName . " ". "הקובץ לא קיים במערכת."];
                $this->customers->setMessages('errors', $target, $msgNotExist);
                $arr['status'] = false;
            }
            if($typeFiles == 'uploade' && ($db || $dir)){ 
                $msgExist = ['fileExist' => $fileName . " ". "קיים במערכת."];
                $arr['status'] = false;
                $this->customers->setMessages('errors', $target, $msgExist);
            }
        }
        return $arr;
    }

    protected function maper(array $files = [], $fn, $customer = false, string $typeFiles = ""){

        $filesKeys = $files && count($files)? array_keys($files):false;
        $maperOb = $filesKeys? array_map(function($attrs) use($files, $fn, $customer, $typeFiles){
            $arr = [];
            if($customer){
                $gals = $customer->gallery;
                $arrItems = [
                    'gallery' => json_decode($gals['image'],true),
                    'loggo' => [$customer->loggo],
                    'video' => json_decode($gals['video'],true)
                ];
                $arr = $arrItems[$attrs];
            }

            $items = isset($files[$attrs]) && count($files[$attrs])? $this->$fn($attrs, $files[$attrs], $arr, $typeFiles): ["no-data"];
            return $items;

        }, $filesKeys): [];

        if($arr = count(array_keys($maperOb, "no-data"))){
            $maperOb = array_splice($maperOb, $arr);
        }

        if($maperOb && count($maperOb) && ! empty($maperOb)) return $maperOb;
    }

    protected function getValidationProps(string $target, array $items = [], array $galFiles = [], string $typeFiles = ""){

        $data = [
            'fileSize' => 0
        ];

        foreach ($items as $key => $value) {

            $file = (object) $value['file'];
            $target = (string) $value['target'];
            $fullPath =  (string) $value['fullPath'];
            
            $data['valFils']['items'][$fullPath] = $file;
            $data['valFils']['ruls'][$fullPath] = ($target == "loggo")? $this->filesRules['gallery']: $this->filesRules[$target];

            $data['valFilsNames']['items'][$fullPath] = $fullPath;
            $data['valFilsNames']['ruls'][$fullPath] = $this->conFilesAttr[$target]['ruls'];

            $data['fileSize'] = $data['fileSize'] + filesize($file);
        }
        return $data;
    }

    private function validateMinMaxFiles(array $upFiles = [], array $toDelFiles = [], array $galFiles = []){

        $validationsItems = ['gallery', 'video', 'loggo'];

        $filesMaper = array_map(function($target) use($upFiles, $toDelFiles, $galFiles){
            $cUpfiles = isset($upFiles[$target]) && count($upFiles[$target])? $upFiles[$target]: [];
            $cDelFiles = isset($toDelFiles[$target]) && count($toDelFiles[$target])? $toDelFiles[$target]: [];

            $minMax = ($cUpfiles || $cDelFiles)? $this->minMaxFiles($target, $cUpfiles, $cDelFiles, $galFiles): false;
            (! $minMax)? $minMax['allow_store'] = false:"";

            return $minMax;

        }, $validationsItems);

        $columnStatus = array_column($filesMaper, "status");
        $columnStore = array_column($filesMaper, "allow_store");

        $filesMaper = collect($filesMaper)->collapse();

        $filesMaper['status'] = in_array(false, $columnStatus)? false: true;
        $filesMaper['allow_store'] = !$filesMaper['status'] || in_array(false, $columnStore)? false: true;

        return $filesMaper;
    }

    protected function minMaxFiles($target, array $upFiles = [], array $delFiles = [], array $galFiles = []){

        $isLoggoOrVideo = (($target == "loggo") || ($target == "video"))? true:false;
        $convertedMsg = false;
        $isValid = true;
        $countedUpfiles = count($upFiles);
        $countedDelfiles = count($delFiles);
        $countedGalfiles = count($galFiles);
        $msgs = [];

        if($isLoggoOrVideo){
            
            if($countedGalfiles && (($countedUpfiles && ! $countedDelfiles) || (! $countedUpfiles && $countedDelfiles) || ($countedDelfiles > 1))){
                $convertedMsg = $this->conFilesAttr[$target]["name"] 
                . ": חייב להכיל קובץ " 
                .$this->conFilesAttr[$target]["type"] . " " .$this->conFilesAttr[$target]['minMaxFiles']["min"] 
                . " לפחות";
                $isValid = false;
            }else if(($countedUpfiles > 1)){
                $convertedMsg = $this->conFilesAttr[$target]["name"] 
                . ": יכול להכיל " 
                . $this->conFilesAttr[$target]['minMaxFiles']["max"]
                ." קבצי "
                .$this->conFilesAttr[$target]["type"]  
                . " בלבד.";
                $isValid = false;
            }
        }else if($target == "gallery"){

             $totalItemsGal = (($countedGalfiles + $countedUpfiles) - $countedDelfiles);
            if($totalItemsGal < 3){
                
                $convertedMsg = $this->conFilesAttr[$target]["name"] .": "
                . "חייב להכיל " 
                .$this->conFilesAttr[$target]['minMaxFiles']["min"]
                . " קבצי "
                .$this->conFilesAttr[$target]["type"] 
                . " לפחות";
                $isValid = false;
            }else if($totalItemsGal > 12){
                
                $convertedMsg = $this->conFilesAttr[$target]["name"] 
                . ": יכול להכיל " 
                . $this->conFilesAttr[$target]['minMaxFiles']["max"]
                ." קבצי "
                .$this->conFilesAttr[$target]["type"]  
                . " בלבד.";
                $isValid = false;
            }
        }
        ! $isValid? $msgs['status'] = false:"";
        $msgs[$target] = $isValid;
        ! $isValid? $this->customers->setMessages('errors', $target, $convertedMsg): "";
        return $msgs;
    }

    public function destroy(Request $request, $id){
    	return $request;
    	$imgs = Customer::find($id)->gallery->image;
    	$imgs = json_decode($imgs, true);
    	$fd = json_decode($request['filesToDelete'], true);

    	$delFiles = $this->customers->delFromGal($imgs, $fd);
        return ['success' => $delFiles['success']];

    }

    private function validInputs($inputes, $method){

    	$myRequest = [];
        $newValRole = [];
        $massegeSuccess = [];
       
    	foreach($inputes as $key => $value) {
            //if($key == "email") {continue;}
            if(! isset($this->formRoles[$key])) {
                return response()->json(['errors' => $key . " dos not exisst in our system"],200);
            }

            $myRequest[$key] = $value;
            $newValRole[$key] = $this->formRoles[$key];
            $massegeSuccess[$key] = $this->convetedMasseges[$key] . " עודכן בהצלחה.";
            $this->customers->setMessages('success', $key, $massegeSuccess[$key]);
        }
    	
        $val = ($method == "POST")? $this->validateItems($myRequest, $this->formRoles):
                                    $this->validateItems($myRequest, $newValRole);
        return $val;
    }

    
     private function validateItems($reqItems, $ruls){

    	$valFiles = Validator::make($reqItems, $ruls,[
    		'mimetypes' => ":attribute " . " קובץ וידאו זה לא נתמך",
    		'mimes' => ":attribute " . " קובץ תמונה זה לא נתמך"
    	]);
    	
        if($valFiles->fails()) {
            $this->prityMessges($valFiles->errors()->all());
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
            $target;
            $message;

            if($targetLoggo){

                $target = $targetLoggo;
                $fNameAndMsg = explode($targetLoggo.'/', $value)[1];
                $fName = explode(' ', $fNameAndMsg)[0];
                $msg = explode($fName, $fNameAndMsg)[1];
                $message = [$targetLoggo => $fName . $msg];
            }else if($targetVideo){

                $target = $targetVideo;

                $fNameAndMsg = explode($targetVideo.'/', $value)[1];
                $fName = explode(' ', $fNameAndMsg)[0];
                $msg = explode($fName, $fNameAndMsg)[1];

                $message = [$targetVideo => $fName . $msg];
            }else if($targetGallery){

                $target = $targetGallery;
                $fNameAndMsg = explode($targetGallery.'/', $value)[1];
                $fName = explode(' ', $fNameAndMsg)[1];
                $msg = explode($fName, $fNameAndMsg)[1];
                $message = [$targetGallery => $fName . $msg];
            }else{

                $exp = explode('The ', $value);
                $targetAttr = explode(' ', $exp[1])[0];
                $target = $targetAttr;
                $message = $exp[1];
            }
            ($target)? $this->customers->setMessages('errors', $target, $message): '';
    	}
	}
    	

	private function sizeGratherThen($size)
	{
        
	    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	    $e = floor(log($size, 1024));

	    $roundedeSize = round($size/pow(1024, $e), 1, PHP_ROUND_HALF_EVEN);
	    $pow = pow(1024, 2) * 6;
        $isBig =  $size > $pow;
	    return $isBig? $roundedeSize . $s[$e]:false;
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
}

