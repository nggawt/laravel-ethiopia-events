<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Repo\CostumersRepo;
use App\Costumer;
use App\Gallery;
use App\User;
use App\Http\Requests\UpdateCostumersRequest;
use Validator;

class CostumersController extends Controller
{
    protected  $masseges = [
        "errors" => [],
        'success' => []
    ];
    protected $costumers;
    protected $formRoles = [
            "company" => "required|min:3",
            "businessType" => "required|min:3",
            "title" => "required|min:3",
            "contact" => "required|min:3",
            "email" => "required|email",
            "tel" => "required|numeric|min:8",
            "address" => "required|min:3",
            "discription" => "required|min:6"
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
    public function __construct(CostumersRepo $coRepo)
    {
        // $this->middleware('cors');
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        $this->middleware('auth:api', ['only' => ['store','update', 'destroy']]);
        $this->costumers = $coRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$cost = $this->costumers->getCostumers();

        return response()->json($cost,200);
    }


    public function show($id)
    {
    	
    	$cost = Costumer::where('id', $id)->first();
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
        $afterValInputs = $this->validateItems($inputsExceptLoggo,$this->formRoles);
        
        
        $afterValFiles = $this->validatefiles($reqsFils,true);

        if(! $afterValFiles || ! $afterValInputs){
        	$messages = $this->getMessages();
        	return response()->json(["errors" => $messages],200);
        }
        
        /***** handel form input before store into database *****/
        /***** check and return errors if any  *****/

        $costumersDetails = $this->costumers->handelDetails($inputsDecoded);

        if(isset($costumersDetails["errors"]) && count($costumersDetails["errors"])){ 
        	return response()->json($costumersDetails,200);
        }

        /***** download files before store into database *****/
        /***** check and return errors if any before store into database *****/
        // return $reqsFils;
        $files = $this->costumers->downloadFiles($reqsFils,$true = true);
        
        if(isset($files["errors"]) && count($files["errors"])){
        	return response()->json(["errors" => $files['errors']],200);
        }
        
        $files = collect($files)->except('loggo')->toArray();
        $files['image'] = json_encode($files['image']);
        $files['video'] = json_encode($files['video']);
        // return $files;
        /***** store form inputs costumer into database *****/
        Costumer::create($costumersDetails);
        
        sleep(1);
        $costumer_id = Costumer::where('email',$costumersDetails['email'])->first()->id;
        $files['costumer_id'] = $costumer_id;
        
        /***** store form filse costumer into database *****/
        Gallery::create($files);

        return empty($this->masseges["errors"])? response()->json(["massege" => "Ok, your content saved!"],200) : response()->json($this->masseges,200);
    }

    public function update(Request $request, $id){
        
        /****** declare all variables *******/
        $reqMethod = request()->isMethod('patch') || request()->isMethod('put');
        $costumer = Costumer::find($id);
        $user = auth()->user();
        $same = ($costumer->user_id === $user->id)? true:false;
        $reqsFils = $request->except(['token','_method', 'filesToDelete','formInputs']);
        $filesTodelete = $request->only('filesToDelete');

        /****** decode objects *******/
        $fd = $filesTodelete? json_decode($request['filesToDelete'], true):null;
        $formInputs = json_decode($request['formInputs']);
        
        /**** valadete before any task ****/
        $afterValInputs = ($formInputs)? $this->validInputs($formInputs):null;
        $afterValFiles = ($reqsFils)? $this->validatefiles($reqsFils): null;
        
        if((! $afterValInputs && ! is_null($afterValInputs)) || (! $afterValFiles && ! is_null($afterValFiles))){
        	return response()->json(["errors" => $this->masseges["errors"][0]],200);
        }
        //if(! $afterValFiles && ! is_null($afterValFiles)){return response()->json($this->masseges,200);}
        
        /***** delete and update files *****/
    	$fdFiles = $this->costumers->updateFiles($reqsFils, $costumer, $fd);
    	return $fdFiles;
        /**** return deleted and downloaded files errors if any || update success mesages ****/
        if(isset($fdFiles['errors']) && count($fdFiles['errors'])){ 
        	return response()->json(['errors' => $fdFiles['errors']]); 
        }else{
        	//return response()->json($fdFiles); 
        	array_push($this->masseges['success'], $fdFiles);
        }

        /***** if we have input emeil we need to ensure to sync email of user too *****/
        if(isset($myRequest['email'])){
        	$userEmailTeken = User::where('email', $myRequest['email'])->first();

        	if(empty($userEmailTeken)){

        		//$costumer->user->update(['email' => $myRequest['email']]);
        	}else{
    			return response()->json(['errors' => [ "email"=> array("האימייל כבר קיים במערכת שלנו.")]],200);
        	}
        }
        // $costumer->update($myRequest);
        
    	// $messages = $this->getMessages();
        return response()->json(['success' => $this->$masseges],200);
    }

    private function getMessages(){

    	$messages = count($this->masseges['success'])? $this->masseges['success']:$this->masseges['errors'];
    	$msgs = [];
    	foreach ($messages as $key => $value) {
    		# code...

    		foreach ($value as $valKey => $valValue) {
    			# code...
    			if(! empty($value[$valKey])) $msgs[$valKey] = $valValue;
    		}
    		
    	}
    	return $msgs;
    }

    public function destroy(Request $request, $id){
    	return $request;
    	$imgs = Costumer::find($id)->gallery->image;
    	$imgs = json_decode($imgs, true);
    	$fd = json_decode($request['filesToDelete'], true);

    	$delFiles = $this->costumers->delFromGal($imgs, $fd);
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
            $massegeSuccess[$key] = $this->convetedMasseges[$key] . " עודכן בהצלח";

            array_push($this->masseges["success"], [$key => array($massegeSuccess)]);

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
            $msgs = $this->prityMessges($valFiles->errors());
            array_push($this->masseges["errors"],$msgs);
            return false;
        }
        return true;
    }
    private function prityMessges($messages){
    	$msgs = [
    		'gallery' => [],
    		'loggo' => [],
    		'video' => []
    	];
    	
    	foreach ($messages->all() as $key => $value) {
    		
    		$exploded = explode('/', $value)[2];
    		$fNname = explode($exploded.'/', $value)[1];
			array_push($msgs[$exploded], [$exploded => $fNname]);
			
    	}
    	return $msgs;
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
