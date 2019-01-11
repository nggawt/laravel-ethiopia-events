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
            "email" => "required|email",
            "tel" => "required|numeric|min:8",
            "address" => "required|min:4",
            "discription" => "required|min:12",
            "deals" => "required|min:12"
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
        protected $validateFiles = [
        	'gallery' => "required|file|max:5000000|image|mimes:png, jpg, bmp",
        	'video' => 'required|file|max:1000000|mimetypes:video/mp4, video/avi,video/mpeg,video/quicktime'
        ];//png, video/mp4,
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

        $reqsFiles = $request->except(['token','formInputs']);
        $inputs = $request['formInputs'];
        $inputsDecoded = json_decode($inputs,true);
        // $inputs = $inputsDecoded->except('loggo');
		$inputsExceptLoggo = collect($inputsDecoded)->except('loggo')->toArray();
		
        /***** validatefiles before any tasks *****/
        /***** check and return errors if any before download to disk *****/
        $afterValInputs = $this->validInputs($inputsExceptLoggo);
        
        
        $afterValFiles = $this->validatefiles($reqsFiles,true);

        if(! $afterValFiles || ! $afterValInputs){
        	// $valMessages = $this->whileMsgs($this->masseges['errors'], $this->masseges['success']);
        	return response()->json(collect($this->messages)->except('success'),200);
        }
        
        /***** handel form input before store into database *****/
        /***** check and return errors if any  *****/

        $customersDetails = $this->customers->handelDetails($inputsDecoded);

        if(isset($customersDetails["errors"])){ 
        	return response()->json(collect($customersDetails['errors'])->only('inputs'),200);
        }

        /***** download files before store into database *****/
        /***** check and return errors if any before store into database *****/
        
        $files = $this->customers->downloadFiles($reqsFiles,$true = true);
        
        /*if(isset($files["errors"]) && count($files["errors"])){
        	return response()->json(["errors" => $files['errors']],200);
        }
        */
        $downloaded = collect($files)->only('downloaded');

        $filesTsave['image'] = json_encode($downloaded['downloaded']['image']);
        $filesTsave['video'] = json_encode($downloaded['downloaded']['video']);
        
        /***** store form inputs customer into database *****/
        if(! isset($customersDetails["errors"]) && $filesTsave['image'] && $filesTsave['video']){

            Customer::create($customersDetails);
            $this->messages['customer'] = $customersDetails;
            sleep(1);
            $customer_id = Customer::where('email',$customersDetails['email'])->first()->id;
            $filesTsave['customer_id'] = $customer_id;
            
            /***** store form filse customer into database *****/
            Gallery::create($filesTsave);
        }else{
            array_push($this->messages['errors']['files'], ['unexcepted-err' => "unexcepted server error."]);
        } 
        

        /******* get and send back messages ******/
        $filesMessages = collect($files)->only(['errors', 'success']);
        // $fileErrs['errors'] = collect($filesMessages['errors'])->except('inputs');

        // $messagesTosend['errors'] = $this->whileMsgs($this->messages['errors'], $fileErrs['errors']);
        // $messagesTosend['success'] = $this->whileMsgs($this->messages['success'], $filesMessages['success']);

        $haveMessages = (isset($filesMessages['errors']) && count($filesMessages['errors'])) || isset($filesMessages['success']) && count($filesMessages['success'])? true: false;

        if($haveMessages){
            
            return response()->json($this->responseMessages($filesMessages),200);
        } 
        return response()->json($this->messages,200);
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
        $filesToUdate = $request->file('filesToUpdate');
        $filesToUdate = isset($filesToUdate) && ! empty($filesToUdate)? $filesToUdate: [];

        $filesTodelete = $request->only('filesToDelete');
        $filesTodelete = isset($filesTodelete) && ! empty($filesTodelete)? json_decode($request['filesToDelete'], true): [];

        /****** decode objects *******/
        $formInputs = $request->only('formInputs');
        $formInputs = isset($formInputs) && ! empty($formInputs)? json_decode($request['formInputs'], true): [];

        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs($formInputs):null;
        $afterValFiles = (isset($filesToUdate) && count($filesToUdate))? $this->validatefiles($filesToUdate): null;
        $afterValDelFiles = $this->validateDelFiles($filesToUdate, $filesTodelete, $gals);

        
        if(!$afterValDelFiles || (! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValFiles && ! is_null($afterValFiles))){
            // return $this->messages;
        	return response()->json(collect($this->messages)->except('success'),200);
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
        
        if(isset($UpFiles['errors']) && count($UpFiles)){
            
            return response()->json($this->responseMessages($UpFiles),200);
        } 
        return response()->json($this->messages,200);
    }


    private function validateDelFiles($fUp, $fDel, $gal){

        $arr = ['status' => true];
        // return $fDel;
        // $gal = 0;
        foreach ($fDel as $key => $linkArrays) {

            if(count($linkArrays) && ($key == "loggo" || $key == "video")){

                $arr[$key] = $fUp && count($fUp)? $this->looper($fUp, $key, function($param) use($key){
                    return $param? $param:false;
                }): false;

                if($arr[$key] === false){
                    $conHeb = $key == "video"? "וידאו": "תמונה";

                    if(! isset($this->messages['errors'][$key])) $this->messages['errors'][$key] = [];
                    $arr['status'] = $arr[$key];
                    array_push($this->messages['errors'][$key], [$key => $conHeb . " : חייב קובץ אחד לפחות"]);
                }
                
            }else if($key == "gallery"){
                if((count($gal) - count($linkArrays)) < 3){

                    if(! isset($this->messages['errors'][$key])) $this->messages['errors'][$key] = [];
                    $arr['status'] = false;
                    array_push($this->messages['errors'][$key], [$key => " גלריה: חייב מינימום 3 קבצי תמונה"]);
                }
            }
            
        }
        return $arr['status'];
    }


    private function looper($files, $tar, $cbk){
        $arr =[];
        // return count($files[$cbk]);
        foreach ($files as $key => $file) {
            
            $reqName = $file->getClientOriginalName();
            $exPlode = explode(':', $reqName);
            
            $target = $exPlode[0];
            $fileName = $exPlode[count($exPlode) - 1];
            $fullPath = $this->customers->getFullPath($exPlode);

            // if($target == $tar) return true;
            if(is_callable($cbk) && ($tar == "loggo" || $tar == "video")){
                if($target == $tar) return $cbk(true);
            }

        }
       return false;
    }
    private function responseMessages($msgs){
        $messagesTosend = [];
        $messagesTosend['errors'] = $this->whileMsgs($this->messages['errors'], $msgs['errors']);
        $messagesTosend['success'] = $this->whileMsgs($this->messages['success'], $msgs['success']);
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

            if(! $this->formRoles[$key]) {
                return response()->json(['errors' => $key . " dos not exisst in our system"],200);
            }

            $myRequest[$key] = $value;
            $newValRole[$key] = $this->formRoles[$key];
            $massegeSuccess[$key] = $this->convetedMasseges[$key] . " עודכן בהצלחה.";
            if(! isset($this->messages["success"][$key])) $this->messages["success"][$key] = [];
            array_push($this->messages["success"][$key], [$key => $massegeSuccess[$key]]);

        }
    	
        $val = $this->validateItems($myRequest, $newValRole);
        
        // array_push($this->masseges["success"], $massegeSuccess);
        return $val;
    }

    private function validatefiles($reqsFiles,$inputsDecoded = false){
    	// return $reqsFiles;
    	$ruls = [];
        $filesSize = 0;
        $isValid = true;
        $fileToVal = [];

        foreach($reqsFiles as $key => $value){

        	$reqName = $reqsFiles[$key]->getClientOriginalName();
            $exPlode = explode(':', $reqName);
            
            $target = $exPlode[0];
            $fileName = $exPlode[count($exPlode) - 1];
            $fullPath = $this->customers->getFullPath($exPlode);
            
        	$ruls[$fullPath] = ($target == "loggo")?$this->validateFiles['gallery']:
                                $this->validateFiles[$target];
        	
            $fileToVal[$fullPath] = $value;
        	$filesSize += filesize($value);
        }

        if($sizeEx = $filesSize? $this->sizeGratherThen($filesSize): 0){
        	array_push($this->messages["errors"], ["files" => array(['size' => 'נפח הקבצים גדול מידי : ' . $sizeEx])]);
        	$isValid = false;
        }
        if(count($reqsFiles) < 5 && $inputsDecoded){
        	array_push($this->messages["errors"], ["files" => array(['min_files' => 'missing pramaters to create account'])]);
        	$isValid = false;
        } 
        // return $fileToVal;
        $valFiles = $this->validateItems($fileToVal, $ruls);
        
        return $isValid? $valFiles: $isValid;
    }

    /***** validateInputsbefore any tasks *****/
     private function validateItems($reqItems, $ruls){

    	$valFiles = Validator::make($reqItems, $ruls,[
    		'mimetypes' => ":attribute " . " קובץ וידאו זה לא נתמך",
    		'mimes' => ":attribute " . " קובץ תמונה זה לא נתמך"
    	]);
    	
        if($valFiles->fails()) {
            $msgs = $this->prityMessges($valFiles->errors()->all());
            // $msgs = $valFiles->errors()->all();
            //$val = $this->whileMsgs($msgs[0], $msgs);
            // array_push($this->messages["errors"], $valFiles->errors()->all());
            // return $valFiles->errors()->all();
            return false;
        }
        return true;
    }

    private function prityMessges($messages){


		foreach ($messages as $key => $value) {
		
    		$filesTarget = (strpos($value , 'gallery') !== false)? 'gallery': 
                            (strpos($value , 'loggo') !== false)? 'loggo': (strpos($value , 'video') !== false)? 'video':false;

    		$target = false;

    		$inputsTarget = (strpos($value , 'target=') !== false)? explode('target=', $value)[1]:false;

    		if($filesTarget){

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

    		
			if(! isset($this->messages["errors"][$target])) $this->messages["errors"][$target] = [];
			array_push($this->messages["errors"][$target],[$target => $fNname]);
    	}
	}
	
    	

	private function sizeGratherThen($size)
	{
        
	    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	    $e = floor(log($size, 1024));

	    $roundedeSize = round($size/pow(1024, $e), 1, PHP_ROUND_HALF_EVEN);
	    $pow = $size  > pow(1024, 6);

	    return $pow? $roundedeSize.$s[$e]:false;
	}

}
