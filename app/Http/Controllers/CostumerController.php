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
        $files = $this->costumers->downloadFiles($reqsFils);

        if(isset($costumersDetails["massege"])) $this->masseges['costumerMsg'] = $costumersDetails["massege"];

        if(count($this->masseges)){
            return response()->json($this->masseges,200);
        }
        
        Costumer::create($costumersDetails);
        $costumer_id = Costumer::where('email',$costumersDetails['email'])->first()->id;
        
        sleep(1);

        $gal = [
            'costumer_id' => $costumer_id,
            'image' => $files['img'],
            'video' => $files['vid']
        ];
        Gallery::create($gal);

        return empty($this->masseges)? response()->json(["massege" => "Ok, your content saved!"],200) : response()->json($this->masseges,200);
    }

    

    public function update(UpdateCostumersRequest $request, $id){
        //valdate
        // $user = auth()->user();
        //return response()->json(["massege" => $user],200);

        //store
        $method = request()->method();

        if (request()->isMethod('patch')) {

            $req = request()->all();
            $key = key($req);
            $data = [$key => $req[$key]];

            Costumer::find($id)->update($data);
        }else{
            Costumer::find($id)->update($request->all());
        }
        return response()->json(["massege" => 'success', 'costumer' => Costumer::find($id) ],200);
    }

    

}
