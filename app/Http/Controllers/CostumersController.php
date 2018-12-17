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
    protected  $masseges = [
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
            "discription" => "required|min:12"
        ];
	protected  $convetedMasseges = [
           "company" => "שם חברה",
           "businessType" => "סוג העסק",
           "title" => "כותרת",
           "contact" => "איש קשר",
           "email" => "אימייל",
           "tel" => "פלאפון או טלפון",
           "address" => "כתובת",
           "discription" => "אודות"
        ];
        protected $validateFiles = [
        	'galleries' => "required|file|max:5000000|image|mimes:png,jpeg,bmp",
        	'video' => 'required|file|max:1000000|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime'
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

        $reqsFils = $request->except(['token','formInputs']);
        $inputs = $request['formInputs'];
        $inputsDecoded = json_decode($inputs,true);
        // $inputs = $inputsDecoded->except('loggo');
		$inputsExceptLoggo = collect($inputsDecoded)->except('loggo')->toArray();
		
        /***** validatefiles before any tasks *****/
        /***** check and return errors if any before download to disk *****/
        $afterValInputs = $this->validInputs($inputsExceptLoggo);
        
        
        $afterValFiles = $this->validatefiles($reqsFils,true);

        if(! $afterValFiles || ! $afterValInputs){
        	// $valMessages = $this->whileMsgs($this->masseges['errors'], $this->masseges['success']);
        	return response()->json(collect($this->masseges)->except('success'),200);
        }
        
        /***** handel form input before store into database *****/
        /***** check and return errors if any  *****/

        $customersDetails = $this->customers->handelDetails($inputsDecoded);

        if(isset($customersDetails["errors"])){ 
        	return response()->json(collect($customersDetails['errors'])->only('inputs'),200);
        }

        /***** download files before store into database *****/
        /***** check and return errors if any before store into database *****/
        
        $files = $this->customers->downloadFiles($reqsFils,$true = true);
        
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
            sleep(1);
            $customer_id = Customer::where('email',$customersDetails['email'])->first()->id;
            $filesTsave['customer_id'] = $customer_id;
            
            /***** store form filse customer into database *****/
            Gallery::create($filesTsave);
        }else{
            array_push($this->masseges['errors']['files'], ['unexcepted-err' => "unexcepted server error."]);
        } 
        

        /******* get and send back messages ******/
        $filesErrors = collect($files)->only(['errors', 'success']);
        $fileErrs['errors'] = collect($filesErrors['errors'])->except('inputs');

        $messagesTosend['errors'] = $this->whileMsgs($this->masseges['errors'], $fileErrs['errors']);
        $messagesTosend['success'] = $this->whileMsgs($this->masseges['success'], $filesErrors['success']);

        return response()->json($messagesTosend,200);
        // return empty($this->masseges["errors"])? response()->json(["massege" => "Ok, your content saved!"],200) : response()->json($this->masseges,200);
    }

    public function update(Request $request, $id){
        
        /****** declare all variables *******/
        $reqMethod = request()->isMethod('patch') || request()->isMethod('put');
        $customer = Customer::find($id);
        $user = auth()->user();
        $same = ($customer->user_id === $user->id)? true:false;
        $reqsFils = $request->except(['token','_method', 'filesToDelete','formInputs']);
        $filesTodelete = $request->only('filesToDelete');

        /****** decode objects *******/
        $fd = $filesTodelete? json_decode($request['filesToDelete'], true):null;
        $formInputs = json_decode($request['formInputs'], true);
        
        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs($formInputs):null;
        $afterValFiles = ($reqsFils)? $this->validatefiles($reqsFils): null;
        
        if((! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValFiles && ! is_null($afterValFiles))){

        	return response()->json(collect($this->masseges)->except('success'),200);
        }
        //if(! $afterValFiles && ! is_null($afterValFiles)){return response()->json($this->masseges,200);}
        
        /***** delete and update files *****/
    	$UpFiles = ((isset($reqsFils) && ! empty($reqsFils)) || (isset($reqsFils) && ! empty($fd))) ? $this->customers->updateFiles($reqsFils, $customer, $fd):[];
        // return ["hhhjj" => $UpFiles];
    	
        /**** return deleted and downloaded files errors if any || update success mesages ****/

        /*if(isset($UpFiles['errors']) && count($UpFiles['errors'])){ 
        	return response()->json(['errors' => $UpFiles['errors']]); 
        }else{
        	//return response()->json($UpFiles); 
        	array_push($this->masseges['success'], $UpFiles);
        }
*/
        
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
        if($inputsIsValidated) $customer->update($formInputs);
        

        $messagesTosend['errors'] = $this->whileMsgs($this->masseges['errors'], $UpFiles['errors']);
        $messagesTosend['success'] = $this->whileMsgs($this->masseges['success'], $UpFiles['success']);

        return response()->json($messagesTosend,200);
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

    /*private function getMessages($firstArgMsgs, $secondArgMsgs){

    	$countArgFirstMsgs = count($firstArgMsgs);
    	$countArgSecondMsgs = count($secondArgMsgs);
    	$msgs = [];
    	
    	
    	foreach ($secondArgMsgs as $key => $value) {
    		
    		if(count($value)) $msgs[$key] = $value;

    		if(count($secondArgMsgs) && $countArgFirstMsgs){

    			foreach ($firstArgMsgs as $valKey => $obToMerge) {

    				(! isset($msgs[$valKey]) && count($firstArgMsgs[$valKey]))? $msgs[$valKey] = $firstArgMsgs[$valKey]:
    				(count($firstArgMsgs[$valKey]) && $firstArgMsgs[$valKey][0] === $msgs[$valKey][0]) ? "":
    				array_push($msgs[$valKey], $firstArgMsgs[$valKey]);
    			}
    		}
    	}
    	return $msgs;
    }

    private function getMsg($obToMerge)
    {
    	$msgs = [];
    	foreach ($obToMerge as $keys => $keyValue) {
    					//return $valValue;
			(count($obToMerge[$keys]) && ! isset($msgs[$keys]))? $msgs[$keys] = $obToMerge[$keys]:
			 isset($msgs[$keys])? array_push($msgs[$keys], $obToMerge[$keys]): "";
			
		}
		return $msgs;
    }*/

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
            if(! isset($this->masseges["success"][$key])) $this->masseges["success"][$key] = [];
            array_push($this->masseges["success"][$key], [$key => $massegeSuccess[$key]]);

        }
    	
        $val = $this->validateItems($myRequest, $newValRole);
        
        // array_push($this->masseges["success"], $massegeSuccess);
        return $val;
    }

    private function validatefiles($reqsFils,$inputsDecoded = false){
    	// return $reqsFils;
    	$filesTovalidate = [];
        $filesSize = 0;
        
        $isValid = true;
        // return true;

        foreach($reqsFils as $key => $value){
        	
        	if(strpos($key, 'galleries') !== false || strpos($key, 'loggo') !== false) $filesTovalidate[$key] = $this->validateFiles['galleries'];
        	if(strpos($key, 'video') !== false) $filesTovalidate[$key] = $this->validateFiles['video'];
        	$filesSize += filesize($value);
        }
        
        if($sizeEx = $this->sizeGratherThen($filesSize)){
        	array_push($this->masseges["errors"], ["files" => array(['size' => 'נפח הקבצים גדול מידי : ' . $sizeEx])]);
        	$isValid = false;
        }
        if(count($reqsFils) < 5 && $inputsDecoded){
        	array_push($this->masseges["errors"], ["files" => array(['min_files' => 'missing pramaters to create account'])]);
        	$isValid = false;
        } 

        $valFiles = $this->validateItems($reqsFils, $filesTovalidate);
        
        return (!$valFiles) ?$valFiles:$isValid;
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
            // array_push($this->masseges["errors"], $valFiles->errors()->all());
            return false;
        }
        return true;
    }

    private function prityMessges($messages){


		foreach ($messages as $key => $value) {
		
    		$filesTarget = (strpos($value , 'gallery') !== false)? 'gallery': (strpos($value , 'loggo') !== false)? 'loggo': (strpos($value , 'video') !== false)? 'video':false;

    		$target = false;

    		$inputsTarget = (strpos($value , 'target=') !== false)? explode('target=', $value)[1]:false;

    		if($filesTarget){

    			$target = explode('/', $value)[2];
    			$fNname = explode($target.'/', $value)[1];
    			
    		}else if($inputsTarget){
    			
    			$target = explode(' ', $inputsTarget)[0];
    			$fNname = explode($target.' ', $value)[1];
    			if(isset($this->masseges['success'][$target])) unset($this->masseges['success'][$target]);
    		}else{
    			$target = "שגיאה";
    			$fNname = " שגיאה בלתי צפוייה בדוק ערכים שנשלחו";
    		}

    		
    		$err = [$target => $fNname];

			if(! isset($this->masseges["errors"][$target])) $this->masseges["errors"][$target] = [];
			array_push($this->masseges["errors"][$target],$err);
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
