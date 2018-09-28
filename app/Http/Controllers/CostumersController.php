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
    protected $dataUrl = "./assets/pages/costumers/";
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
        	'galleries' => "required|file|max:5000000|image|mimes:jpeg,bmp,png",
        	'video' => 'required|file|max:6000000|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime'
        ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CostumersRepo $coRepo)
    {
        // $this->middleware('cors');
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        $this->middleware('auth:api', ['only' => ['store','update']]);
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
        $reqsDetails = $request->only(['formInputs']);
        $inputsDecoded = json_decode($reqsDetails['formInputs'],true);

        /***** validatefiles before any tasks *****/
        
        $afterValFiles = $this->validatefiles($reqsFils,$inputsDecoded);
        
        /***** return valadation errors if any *****/
        if(! $afterValFiles){return response()->json($this->masseges,200);}
        
        /***** handel form input before store into database *****/
        $costumersDetails = $this->costumers->handelDetails($inputsDecoded);

        /***** download files before store into database *****/
        $files = $this->costumers->downloadFiles($reqsFils);

        /***** check and return errors if any before store into database *****/
        if(isset($costumersDetails["errors"]) && count($costumersDetails["errors"])){ return response()->json($costumersDetails,200);}
        if(isset($files["errors"]) && count($files["errors"])) { return response()->json($files,200);}
		//return response()->json(["massege" => "Ok, your content saved!"],200);
        /***** store form inputs costumer into database *****/
        Costumer::create($costumersDetails);
        
        sleep(1);
        $costumer_id = Costumer::where('email',$costumersDetails['email'])->first()->id;
        $files['costumer_id'] = $costumer_id;
        
        /***** store form filse costumer into database *****/
        Gallery::create($files);

        return empty($this->masseges["errors"])? response()->json(["massege" => "Ok, your content saved!"],200) : response()->json($this->masseges,200);
    }

    

    public function update(UpdateCostumersRequest $request, $id){
        //valdate
        //$this->validate()
        
        //$method = request()->method();

        if (request()->isMethod('patch') || request()->isMethod('put')) {

            $req = request()->all();
            $myRequest = [];
            $newValRole = [];
            $massegeSuccess = [];

            foreach($req as $key => $value) {

                if(! $this->formRoles[$key]) {
                    return response()->json(['errors' => $key . " dos not exisst in our system"],200);
                }

                $myRequest[$key] = $value;
                $newValRole[$key] = $this->formRoles[$key];
                $massegeSuccess[$key] = $this->convetedMasseges[$key] . " עודכן בהצלח";

            }
        	// return response()->json(['masseges' => $myRequest],200);

            $val = Validator::make($myRequest, $newValRole);
            if ($val->fails()) {
                
                return response()->json(["errors" => $val->errors()],200);
            }
            
            $costumer = Costumer::find($id);
            if(isset($myRequest['email'])){
            	$userEmailTeken = User::where('email', $myRequest['email'])->first();

            	if(empty($userEmailTeken)){

            		$costumer->user->update([
                    	'email' => $myRequest['email']
                	]);
            	}else{
        			return response()->json(['errors' => [ "email"=> array("האימייל כבר קיים במערכת שלנו.")]],200);
            	}
                
            }
            $costumer->update($myRequest);
            return response()->json(['success' => $massegeSuccess],200);
        }else{
            Costumer::find($id)->update($request->all());
        }
        return response()->json(["masseges" => 'success', 'costumer' => Costumer::find($id) ],200);
    }

    public function delete(UpdateCostumersRequest $request, $id){

    }

    private function validatefiles($reqsFils,$inputsDecoded){

    	$filesTovalidate = [];
        $filesSize = 0;
        $isValid = $this->validateInputs($inputsDecoded);

        foreach($reqsFils as $key => $value){
        	
        	if(strpos($key, 'galleries') !== false || strpos($key, 'loggo') !== false) $filesTovalidate[$key] = $this->validateFiles['galleries'];
        	if(strpos($key, 'video') !== false) $filesTovalidate[$key] = $this->validateFiles['video'];
        	$filesSize += filesize($value);
        }
        
        if($filesSize > 6000000){
        	array_push($this->masseges["errors"], ["filse" => array('size' => 'the files is greater then 6 mb')]);
        	$isValid = false;
        }
        if(count($reqsFils) < 5){
        	array_push($this->masseges["errors"], ["filse" => array('min_files' => 'missing pramaters to create account')]);
        	$isValid = false;
        } 

        $valFiles = Validator::make($reqsFils, $filesTovalidate);
        if ($valFiles->fails()) {
            array_push($this->masseges["errors"],$valFiles->errors());
            $isValid = false;
        }
        
        return $isValid;
    }

    /***** validateInputsbefore any tasks *****/
     private function validateInputs($reqsFils){

    	$valFiles = Validator::make($reqsFils, $this->formRoles);

        if($valFiles->fails()) {
            
            array_push($this->masseges["errors"],$valFiles->errors());
            return false;
        }
        return true;
    }
}
