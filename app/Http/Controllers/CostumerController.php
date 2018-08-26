<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Costumer;
use App\Gallery;
use App\Video;
use App\User;

class CostumerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('cors');
        $this->middleware('auth:api', ['only' => ['store']]);
        // $this->middleware('auth:api', ['only' => ['store']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$cost['costumers'] = $this->filterCostumers(Costumer::all());
        $cost['galleries'] = $this->filterGalleries(Gallery::all());
        $cost['videos'] = $this->filterVideos(Video::all());

        return response()->json($cost,200);
    }

    private function filterCostumers($costumers){
        $filteredCostumers = [];

        foreach ($costumers as $value) {
            # code...
            $en = [
                'id' => $value->id,
                'user_id' => $value->user_id,
                'company' => $value->company,
                'businessType' => $value->businessType,
                'title' => $value->title,
                'contact' => $value->contact,
                'loggo' => $value->loggo,
                'email' => $value->email,
                'discription' => $value->discription,
                'address' => $value->address,
                'tel' => $value->tel,
                'deals' => $value->deals

            ];
            $filteredCostumers[] = $en;
        }
        return $filteredCostumers;
    }

    private function filterGalleries($galleries){

        $filterGalleries = [];
        foreach ($galleries as $gallery) {
            $en = [
                'id' => $gallery->id,
                'costumer_id' => $gallery->costumer_id,
                'image' => $gallery->image
            ];
            $filterGalleries[] = $en;
        }
        return $filterGalleries;
    }

    private function filterVideos($vidoes){
        
        $filterVideos = [];
        foreach ($vidoes as $video) {
            $en = [
                'id' => $video->id,
                'costumer_id' => $video->costumer_id,
                'video' => $video->video
            ];
            $filterVideos[] = $en;
        }
        return $filterVideos;
    }

    public function show($costumerName)
    {
    	
    	$cost = Costumer::where('company', $costumerName)->first();
       return response()->json($cost,200);
    }

    public function store(Request $request){
        
        $reqsFils = $request->except(['token','formInputs']);
        $dataUrl = "./assets/pages/costumers/";

        $reqsDetails = $request->only(['formInputs']);
        $costumersDetails = $this->handelDetails($reqsDetails);
        
        $masseges = [];

        foreach($reqsFils as $key => $value){

            $target = explode('/', $key)[2];
            $fileName = $key.'.'. ($value)->getClientOriginalExtension();
            // $fileName = $key.'.'. ($value)->extension();


            if(! Storage::disk('public')->exists($fileName) && $value->isValid()){
                
                
                if($target === "video"){
                    $video = new Video;
                    $video->costumer_id = User::where('email',$costumersDetails['email'])->first()->id;
                    $video->video = $dataUrl . $fileName;
                    $video->save();
                }else if($target === "galleries"){
                    $galleries = new Gallery;
                    $galleries->costumer_id = User::where('email',$costumersDetails['email'])->first()->id;
                    $galleries->image = $dataUrl . $fileName;
                    $galleries->save();
                }
                
                Storage::putFileAs('public/', new File($value), $fileName);

            }else if(Storage::disk('public')->exists($fileName)){
                $masseges[$key] = 'The File <' . $key .":> exists in our storage From BUzzi";
            }else{
                $masseges["error"] = "Error occurred while Uploading the File";
            }
        }
        // $galleries->save();
        Costumer::create($costumersDetails);

        return empty($masseges)? response()->json(["massege", "Ok, your content saved!"],200) : response()->json($masseges,200);
    }

    private function handelDetails($costumers){
        $dataUrl = "./assets/pages/costumers/";
        $data = json_decode($costumers['formInputs']);
        $costumerArray = [];
        foreach($data as $value){

            $costumerArray['user_id'] = User::where('email',$data->email)->first();
            $costumerArray['company'] = $data->company;
            $costumerArray['businessType'] = $data->businessType;
            $costumerArray['title'] = $data->title;
            $costumerArray['loggo'] = $dataUrl . $data->loggo;
            $costumerArray['contact'] = $data->contact;
            $costumerArray['discription'] = $data->about;
            $costumerArray['email'] = $data->email;
            $costumerArray['tel'] = $data->phone;
            $costumerArray['address'] = $data->address;
            $costumerArray['deals'] =  "מבצעים בהמשך.";
           
        }
       return $costumerArray;
    }

}
