<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Repo\CostumersRepo;
use App\Costumer;
use App\Gallery;
use App\Video;
use App\User;
use App\Http\Requests\UpdateCostumersRequest;

class CostumerController extends Controller
{
    protected $dataUrl = "./assets/pages/costumers/";
    protected  $masseges = [];
    protected $costumers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CostumersRepo $coRepo)
    {
        // $this->middleware('cors');
        // $this->middleware('auth:api', ['only' => ['store']]);
        $this->middleware('auth:api', ['only' => ['store']]);
        $this->costumers = $coRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->costumers;
    	$cost = $this->costumers->getCostumers();

        return response()->json($cost,200);
    }

    

    public function show($costumerName)
    {
    	
    	$cost = Costumer::where('company', $costumerName)->first();
       return response()->json($cost,200);
    }

    public function store(Request $request){
        
        $reqsFils = $request->except(['token','formInputs']);

        $reqsDetails = $request->only(['formInputs']);

        $costumersDetails = $this->costumers->handelDetails($reqsDetails);
        //return response()->json($costumersDetails,200);
        //$costumerExisst = isset($costumersDetails["email"]) && Costumer::where("email",$costumersDetails["email"])->first()->email === auth()->user()->email;

        /*if($costumerExisst){
            return response()->json(["massege" => "you already our costumer"],200);
        }*/
        if(isset($costumersDetails["massege"])) $this->masseges['costumerMsg'] = $costumersDetails["massege"];

        if(count($this->masseges)){
            return response()->json($this->masseges,200);
        }
        
        Costumer::create($costumersDetails);
        sleep(1);
        foreach($reqsFils as $key => $value){

            $target = explode('/', $key)[2];
            $fileName = $key.'.'. ($value)->extension();
            $costumer_id = Costumer::where('email',$costumersDetails['email'])->first()->id;
           
            if(! Storage::disk('public')->exists($fileName) && $value->isValid()){
                
                if($target === "video"){
                    
                    $vid = [
                        'costumer_id' => $costumer_id,
                        'video' => $this->dataUrl . $fileName
                    ];
                    
                    Video::create($vid);
                   
                }else if($target === "galleries"){
                    
                    $gal = [
                        'costumer_id' => $costumer_id,
                        'image' => $this->dataUrl . $fileName
                    ];
                    
                    Gallery::create($gal);
                }
                
                Storage::putFileAs('public/', new File($value), $fileName);

            }else if(Storage::disk('public')->exists($fileName)){
                $this->masseges[$key] = "The File  exists in our storage From BUzzi";
            }else{
                $this->masseges["massege"] = "Error occurred while Uploading the File";
            }
        }
        // $galleries->save();
        //Costumer::create($costumersDetails);

        return empty($this->masseges)? response()->json(["massege" => "Ok, your content saved!"],200) : response()->json($this->masseges,200);
    }

    

    public function update(UpdateCostumersRequest $request, $id){
        //valdate
        // $user = auth()->user();
        //return response()->json(["massege" => $user],200);

        //store
        Costumer::find($id)->update($request->all());

        //response
        return response()->json(["massege" => Costumer::find($id)],200);
    }

}
