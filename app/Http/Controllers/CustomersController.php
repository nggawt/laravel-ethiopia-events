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
use App\Rules\filesSize;
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
            "owner" => "numeric",
            "businessType" => "required|string|min:3",
            "title" => "required|string|min:4",
            "contact" => "required|string|min:3",
            "email" => "required|string|email|unique:customers,email",
            "tel" => "required|string|numeric|min:8",
            "address" => "required|string|min:4",
            "descriptions" => "required|string|min:12",
            "deals" => "required|string|min:3",
            "confirmed" => "boolean"
        ];
	protected  $convetedMasseges = [
           "company" => "שם חברה",
           "businessType" => "סוג העסק",
           "title" => "כותרת",
           "contact" => "איש קשר",
           "email" => "אימייל",
           "tel" => "פלאפון או טלפון",
           "address" => "כתובת",
           "descriptions" => "אודות",
           "deals" => "מבצעים",
           "owner" => "משתמש  ",
           "confirmed" => "סטטוס "
        ];
        protected $filesRules = [
            'images' => "required|file|between:10,4000|image|mimes:png,jpg",
            "loggo" => "same:images",
        	'video' => "required|file|between:200,6000|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime"
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
                "images" => [
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
        $this->middleware('auth:api', ['except' => ['index', 'store']]);
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        // $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
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
        //looper($target, $files, $fn)
        $items = $this->customers->looper($tar, $files,'downloadFiles');
        return $items;
    }

    public function store(Request $request){

        // if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        /****** grab and decode exissted objects *******/
        $authUser = auth('api')->user();
        $authAdmin = auth('admin')->user();

        $files = $request->file('files');
        $files = isset($files) && ! empty($files)? $this->customers->getFilesParams($files): false;
        $method = $request->method();
        // $filesToUdate = $request->file('files');
        // $filesToUdate = isset($filesToUdate) && ! empty($filesToUdate)? $this->customers->getFilesParams($filesToUdate): [];
        
        /****** decode objects *******/
        $formInputs = $request->only('formInputs');
        
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($formInputs['formInputs'], true): false;

        /**** send message errors if rquires params not exisst ****/
        if(! $files || ! $formInputs){
            $badRequest = ['badRequest' => array(["request" => "missing data rquest", 'req' => $request->all()])];
            return response()->json(['errors' => $badRequest],200);
        }

        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs(collect($formInputs)->except('loggo'), $method):null;
        // $afterValFiles = (isset($files) && count($files))? $this->mainValidation($files, [], []): null;
        $valItems = ['store'=> $files];

        $afterValFiles = $this->mainValidation($valItems, $method);//$filesToUdate, $filesTodelete, $customer
        

        if((! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValFiles && ! is_null($afterValFiles))){
            // return $this->messages;
            // return response()->json(["afterValInputs" => $afterValInputs, "afterValFiles" => $afterValFiles],200);
            return response()->json(collect($this->customers->getMessages())->except('success'),200);
            // return response()->json($this->customers->getMessages(),200);
        }
        
        /***** download files before store into database *****/
        $filesKeys = array_keys($files);
        $download = array_map([$this, 'mapItems'], $files, $filesKeys);
        
        $downloaded = collect($download)->collapse();

        // return ['collapsed' => $downloaded, "download" => $download];

        // $filesTsave['image'] = json_encode($downloaded['images']);
        // $filesTsave['video'] = json_encode($downloaded['video']);

        /***** store form inputs customer into database *****/
        if($downloaded['images'] && $downloaded['video']){
            // $loggo = $files['loggo'][0];
            // $loggoUrl = $loggo['fullUrl'].'.'.$loggo['ext'];./assets/pages/customers/salons/jhon-salons/loggo/k&m_web.png
            $formInputs['loggo'] = $downloaded['loggo'][0];//$formInputs

            $formInputs['user_id'] = ($authAdmin && $formInputs['owner'])? (int) $formInputs['owner']:$authUser->id;
            Customer::create($formInputs);
            // $this->messages['customer'] = $customersDetails;
            sleep(1);
            
            $customer_id = Customer::where('email',$formInputs['email'])->first()->id;
            $gallery = [
                'customer_id' => $customer_id,
                'image' => json_encode($downloaded['images']),
                "video" => json_encode($downloaded['video'])
            ];
            /***** store form filse customer into database *****/
            Gallery::create($gallery);
        }else{
            $message = ['files' => ['unexceptedErr' => "unexcepted server error."]];
            // array_push($this->messages['errors'], $message);
            $this->customers->setMessages('errors', "unexcepted", $message);
        } 
        
        /******* get and send back messages ******/
        return response()->json($this->customers->getMessages(),200);
    }

    public function update(Request $request,Customer $customer){
        
        // if(! \Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        $method = $request->method();
        $filesToUdate = $request->file('files');
        $filesTodelete = $request->only('filesToDelete');
        $formInputs = $request->only('formInputs');

        $user = auth('api')->user();
        if($customer->user_id != $user->id) return response()->json(['error' => 'Unauthorized', $customer], 401);

        /****** decode files objects *******/
        $filesTodelete = isset($filesTodelete) && ! empty($filesTodelete)? json_decode($request['filesToDelete'], true): [];
        $files = isset($filesToUdate) && ! empty($filesToUdate)? $this->customers->getFilesParams($filesToUdate): [];
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($request['formInputs'], true): [];

        /***** if we have no data at all return ******/
        if((! $files || empty($files)) && (! $filesTodelete || empty($filesTodelete)) && (! $formInputs || empty($formInputs))){
            $badRequest = ['badRequest' => array(["request" => "missing data rquest"])];
            return response()->json(['errors' => $badRequest],200);
        }

        /**** valadete before any task ****/

        $afterValInputs = ($formInputs)? $this->validInputs($formInputs, $method):null;
        
        // $valFiles = Validator::make($files, [
        //     'images' => ['required','array','min:3','max:12',new filesSize]
        //     // 'fullUrl' => ['required', new filesSize] 
        // ]);

        // return ["val" => $valFiles->errors(), "items" => $files['images'], 'fails' => $valFiles->fails()];

        $valItems = ['update'=> $files, 'delete' => $filesTodelete, 'customer' => $customer];
        $afterValDelFiles = ($files || $filesTodelete)? $this->mainValidation($valItems, $method): null;


        if((! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValDelFiles && ! is_null($afterValDelFiles))){
            
        	// return response()->json(collect($this->messages)->except('success'),200);
            return response()->json($this->customers->getMessages(),200);
        }
        
        /***** update and delete files *****/
        $down = ($files || $filesTodelete)? $this->customers->updateFiles($files, $filesTodelete, $customer): 'no';// "downloadFiles"
        if(isset($down['image']) && count($down['image'])) {
            $customer->gallery['image'] = json_encode($down['image']);
            $customer->gallery->save();
        }
        if(isset($down['video']) && count($down['video'])) {
            $customer->gallery['video'] = json_encode($down['video']);
            $customer->gallery->save();
        }
        if(isset($down['loggo']) && count($down['loggo'])) {
            $loggo = $down['loggo'][0];
            $customer->loggo = $loggo;
            $customer->save();
        }
        // return response()->json(['down' => $down, 'msgs' => $this->customers->getMessages()], 200);

        $formInputs = collect($formInputs)->except('company')->toArray();
        $inputsIsValidated = (isset($formInputs) && count($formInputs));

        if($inputsIsValidated) $customer->update($formInputs);
        /***** if we have input emeil we need to ensure to sync email of user too *****/
        if($inputsIsValidated && isset($formInputs['email'])){
        	$userEmailTeken = User::where('email', $formInputs['email'])->first();

        	if(empty($userEmailTeken)){

        		$customer->user->update(['email' => $formInputs['email']]);
        	}else{
    			return response()->json(['errors' => [ "email"=> array("האימייל כבר קיים במערכת שלנו.")]],200);
        	}
        }
        /******* get and send back messages ******/
        return response()->json($this->customers->getMessages(),200);
    }

    public function destroy(Request $request,Customer $customer){
        return ["request" => $request->all(), "customer" => $customer];
        $imgs = Customer::find($id)->gallery->image;
        $imgs = json_decode($imgs, true);
        $fd = json_decode($request['filesToDelete'], true);

        $delFiles = $this->customers->delFromGal($imgs, $fd);
        return ['success' => $delFiles['success']];

    }

    protected function itemsSave($requestFiles, $uploadedFiles, $gals){

        foreach ($requestFiles as $key => $value) {
            # code...
            if(isset($uploadedFiles[$key]) && count($uploadedFiles[$key])){

            }
        }
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
        # have to update more than allowed
        # have to delete more than allowed
        # have to update and to delete more than allowed
    # have to update but no to delete
        # have to updat more than allowed
    # have to delete but no to update
        # have to delete more than allowed
    # have no update and delete

    private function mainValidation(array $files = [], string $requestMethod){

        
        $isValid = true;
        $methodIsUpdate = ($requestMethod == "PUT" || $requestMethod == "PATCH");
        $upFiles = $methodIsUpdate? $files['update']: $files['store'];
        $customer = [];
        $toDelFiles = [];
        // $upFiles = isset($files['update'])? $files['update']:($requestMethod == "POST" && isset($files['store']))? $files['store']: [];

        if($methodIsUpdate){// $requestMethod
            $toDelFiles = isset($files['delete'])? $files['delete']: [];
            $customer = isset($files['customer'])? $files['customer']: [];
        }
        
        // validate uploaded files
        $items = $this->maper($upFiles, 'getValidationProps', $customer);
        $validateUploadedFiles = $this->validateUploadedFiles($items);
        if(! $validateUploadedFiles) $isValid  = false;

        # validate files to delete exisst
        # files to deleted must be exist in dir and db
        # files uploaded must no exist in dir and db

        $upFilesKeys = isset($upFiles) && count($upFiles)? true:false;
        $delFilesKeys = isset($toDelFiles) && count($toDelFiles)? true:false;


        $map['del'] = $delFilesKeys? $this->maper($toDelFiles, 'isExist', $customer, 'delete'): [];
        $map['up'] = $upFilesKeys? $this->maper($upFiles, 'isExist', $customer, 'uploade'): [];
        
        // $this->customers->setMessages('errors', 'TEST357', $map);
        $statusDel = array_column($map['del'], 'status');
        $statusUp = array_column($map['up'], 'status');

        # before production need change status variable conditions to not oparator;
        $exist = in_array(false, $statusUp) || in_array(false, $statusDel)? false: true;//in_array(false, $statusDel) && 
        
        /**** validate min-maxFile ****/
        $minMaxfiles = $this->validateMinMaxFiles($upFiles, $toDelFiles, $customer);
        $minMaxStatus = $minMaxfiles['status'] && $requestMethod == "POST"? $minMaxfiles['allow_store']: $minMaxfiles['status'];
        (! $minMaxStatus)? $this->customers->setMessages('errors', 'minMaxFile', $minMaxfiles): "";

        (! $validateUploadedFiles || ! $exist || ! $minMaxStatus)? $isValid = false: '';
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

            $dir = $this->customers->fileExist($itemDir, $file);
            $db = in_array($item, $exisstedFiles);

            $arr[$fileName] = ['db' => $db, 'dir' => $dir];

            if($typeFiles == 'delete' && (! $db || ! $dir)){ 
                $msgNotExist = ['file_not_Exist' => $fileName . " ". "הקובץ לא קיים במערכת. " . $typeFiles . " " .$value];
                $this->customers->setMessages('errors', $target, $msgNotExist);
                $arr['status'] = false;
            }
            if($typeFiles == 'uploade' && ($db || $dir)){ 
                $msgExist = ['file_Exist' => $fileName . " ". "קיים במערכת."];
                $arr['status'] = false;
                $this->customers->setMessages('errors', $target, $msgExist);
            }
        }
        return $arr;
    }

    protected function validateUploadedFiles($items){
        $validateFils = [];
        $validateFilsNames = [];
        $filesSize = 0;

        if($items && count($items)){
            
            (isset($items['files']['items']))? $validateFils['items'] = $items['files']['items']: "";
            (isset($items['files']['ruls']))? $validateFils['ruls'] = $items['files']['ruls']: "";

            (isset($items['names']['items']))? $validateFilsNames['items'] = $items['names']['items']: "";
            (isset($items['names']['ruls']))? $validateFilsNames['ruls'] = $items['names']['ruls']: "";

            (isset($items['fileSize']))? $filesSize = $filesSize + $items['fileSize']: "";
        }

        /**** validate total filesSizes ****/
        if($sizeEx = $filesSize? $this->sizeGratherThen($filesSize): 0){
            $message = array('fileSize' => 'נפח הקבצים גדול מידי : ' . $sizeEx);
            $this->customers->setMessages('errors', 'files', $message);
            $isValid = false;
        }

        # validate required ,fileSize ,fileType ,mimeType
        $fileIsValid = count($validateFils)? $this->validateItems($validateFils['items'], $validateFils['ruls']): true;
        
        # validate filesMinMaxCharLen
        $filesCharLenIsValid = count($validateFilsNames)? $this->validateItems($validateFilsNames['items'], $validateFilsNames['ruls']): true;

        return ($fileIsValid && $filesCharLenIsValid)? true: false;
    }

    protected function maper(array $files = [], $fn, $customer, string $typeFiles = ""){
        $isCustomer = $customer && $customer->id? true:false;
        $galItems = [ 
            "images" => $isCustomer? json_decode($customer->gallery->image, true):[],
            "video" => $isCustomer? json_decode($customer->gallery->video, true):[],
            "loggo" => $isCustomer? array($customer->loggo):[]
        ];

        $maperOb = [];
        foreach ($files as $key => $value) {
            
            count($value)? $maperOb[$key] = $this->$fn($key, $value, $galItems[$key], $typeFiles): '';
        }
        return $maperOb;
    }

    protected function getValidationProps(string $target, array $items = []){

        $data = [
            'fileSize' => 0
        ];

        foreach ($items as $key => $value) {

            $file = (object) $value['file'];
            $target = (string) $value['target'];
            $fullPath =  (string) $value['fullPath'];
            
            $data['files']['items'][$fullPath] = $file;
            $data['files']['ruls'][$fullPath] = $this->filesRules[$target];

            $data['names']['items'][$fullPath] = $fullPath;
            $data['names']['ruls'][$fullPath] = $this->conFilesAttr[$target]['ruls'];

            $data['fileSize'] = $data['fileSize'] + filesize($file);
        }
        return $data;
    }

    private function validateMinMaxFiles(array $upFiles = [], array $toDelFiles = [], $customer = []){

        $validationsItems = ['images', 'video', 'loggo'];
        
        $isCustomer = $customer && $customer->id? true:false;
        $galItems = [ 
            "images" => $isCustomer? json_decode($customer->gallery->image, true):[],
            "video" => $isCustomer? json_decode($customer->gallery->video, true):[],
            "loggo" => $isCustomer? array($customer->loggo):[]
        ];

        $filesMaper = array_map(function($target) use($upFiles, $toDelFiles, $galItems){
            $cUpfiles = isset($upFiles[$target]) && count($upFiles[$target])? $upFiles[$target]: [];
            $cDelFiles = isset($toDelFiles[$target]) && count($toDelFiles[$target])? $toDelFiles[$target]: [];

            $minMax = ($cUpfiles || $cDelFiles)? $this->minMaxFiles($target, $cUpfiles, $cDelFiles, $galItems[$target]): false;
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
        }else if($target == "images"){

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

    private function validInputs($inputes, $method){

    	$myRequest = [];
        $newValRole = [];
        $massegeSuccess = [];
       
    	foreach($inputes as $key => $value) {
            // if($key == "owner") {continue;}
            if(! isset($this->formRoles[$key])) {
                $msg = $key . " dos not exisst in our system";
                $this->customers->setMessages('errors', $key, $msg);
                return false;
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
            //$this->prityMessges($valFiles->errors()->all());
            $this->customers->setErrorsMessages($valFiles);
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
            $targetGallery =  $this->getTarget($value, 'images')? 'images':false;
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

