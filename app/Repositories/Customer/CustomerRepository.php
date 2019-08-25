<?php

namespace App\Repositories\Customer;


use App\Customer;
use App\Gallery;
use App\Repo\traits\Messages;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class CustomerRepository implements CustomerRepoInterface
{
    use Messages;
    protected $customer;
    protected $dataUrl = "./assets/pages/customers/";

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

	/**
     * Get's a article by it's ID
     *
     * @param int
     */
    public function show($id){ }

    /**
     * Get's all articles.
     *
     * @return mixed
     */
    public function all(){

    	return response()->json(['customers' => $this->transformCustomers()], 200);
    }

    public function create(array $customerProps){

        $inputs = $customerProps['formInputs'];
        $files = $customerProps['files'];

        $dowed = $this->fdownload($files, "downloadFiles");

        if(! $dowed) return ['message' => "unknown error"];
         $gal = [
            "customer_id" => $customer->id,
            "images" => json_encode($dowed['images']),
            "video" => json_encode($dowed['video'])
        ];
        $gal = Gallery::create($gal);

        $inputs['user_id'] = auth('api')->user()? auth('api')->user()->id: auth('admin')->user()->id;
        $inputs['confirmed'] = $inputs['confirmed']? 1: 0;
        $inputs['slug'] = slug_heb($inputs['title']);
        $inputs['loggo'] = $dowed['loggo'][0];

        $customer = $this->customer->create($inputs);
        // sleep(1);
        
        return response()->json(["message" => "your customer/post was created succesfully!", "status" => true, 'customer' => $customer], 200);
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update(array $items, $id){
        $fuploaded = isset($items['files'])? $items['files']: [];
        $inputs = isset($items['formInputs'])? $items['formInputs']: [];
        $fTodelete = isset($items['filesToDelete'])? $items['filesToDelete']: [];

        $fdownloaded = $this->fdownload($fuploaded, "downloadFiles");
        if(! $fdownloaded) return ['message' => "unknown error"];

        $customer = $this->customer->findOrfail($id);
        $gal = $this->upgallery($customer, $fuploaded, $fTodelete);
    	isset($inputs['title'])? $inputs['slug'] = slug_heb($inputs['title']): '';
        Arr::has($inputs, 'confirmed')? $inputs['confirmed'] = $inputs['confirmed']? 1: 0: '';

        $customer->update($inputs);
        return true;
    }

    protected function fdownload($files, $fn){

        $dowed = collect($files)->map(function($value, $key) use($fn){
            $download = $this->looper($key, $value, $fn)[$key];
            return in_array(false, $download)? false :$download;
        });

        $pass = $dowed->every(function($value, $key){
            return $value !== false;
        });
        return $pass? $dowed: $pass;
    }

    protected function upgallery($customer, $fu, $fd){
        $customerGal = $customer->gallery;
        $images = json_decode($customerGal->images, true);
        $video = json_decode($customerGal->video, true);
        $gal = [
            "customer_id" => $customer->id
        ];

        $deleted = $this->fdownload($fd, 'deleteFromStorage');
        if(isset($deleted['images'])) {
            $upImages = isset($fu['images'])? array_keys($fu['images']): false;
            $mergedImages = $upImages? array_merge($images, $upImages): $images;
            $diff =  array_diff($mergedImages, $deleted['images']);
            $gal["images"] = json_encode($diff);
            // return $diff;
        };

        if(isset($deleted['video'])) {
            $upvideo = isset($fu['video'])? array_keys($fu['video']): false;
            $mergedvideo = $upvideo? array_merge($video, $upvideo): $video;
            $diff =  array_diff($mergedvideo, $deleted['video']);
            $gal["video"] = json_encode($diff);
        };

        if(isset($deleted['loggo'])) {
            // $logg = array_keys($fu['loggo'])[0];
            $customer->loggo = array_keys($fu['loggo'])[0];
            $customer->save();
            //$gal["loggo"] = $logg;//json_encode($diff);
        };
        $gal = $customerGal->update($gal);
        return $gal;
    }

    protected function deleteFromStorage($target, $key, $link, $nameFlag = false){// $target, string $link = ''
        $deletedItems = [];
        // $posTarget = (strpos($link , $target.'/') === false)? "gallery/": $target.'/';

        $fileName = explode($target.'/' , $link)[1];
        
        $linkToFile = explode('customers/', $link)[1];

        $deleted = Storage::disk('customers')->delete($linkToFile);

        // $messege = ['deletedFiles' => $fileName, "status" => $deleted];
        // $this->setMessages('success', $target, $messege);
        
        //$deletedItems[$target] =//$deleted
        return  $deleted? $link: $deleted; // $deletedItems;
    }

    /**
     * Deletes a article.
     *
     * @param int
     */
    public function delete($id){

    }

    protected function looper($target, $files, $fn){

        $fnResponse = [];
        foreach ($files as $key => $value) {

            $item = $this->$fn($target, $key, $value, true); 
            if(! isset($fnResponse[$target])) $fnResponse[$target] = [];
            array_push($fnResponse[$target], $item);
        }
        return $fnResponse;
    }

    protected function downloadFiles($target, $url, $file, $nameFlag = false){

        // $downloadedFiles = [];
        // $file = $files['file'];
        $path =  $url;// . '.' . ($file)->extension();
        $exp = explode('/', $path);

        $name = $exp[count($exp) - 1];
        $fullUrl = $this->dataUrl . $path;

        $urlToSave = ($nameFlag)? $fullUrl: $name;
        $truly = [true, false];//$truly[rand(0, 1)];
        $down = Storage::putFileAs('customers', new File($file), $path);//
        // $messege = [$target => "הקובץ עודכן בהצלחה " .$name ,'status' => $down];
        
        // $downloadedFiles = $down? $urlToSave: $down;
        // $this->setMessages('success', $target, $messege);
        return $down? $urlToSave: $down;
    }

    protected function transformCustomers(){

    	return $this->customer->all()->reduce(function($total, $customer){

            $businessType = slug_heb($customer->businessType);
            $customers = $this->getCustomersProps($customer);

            isset($total[$businessType])? array_push($total[$businessType], $customers): $total[$businessType] = [$customers];
            return $total;
        });
    }

    protected function objectToArray($items){
        $arr = [];
        foreach ($items as $key => $value) {
            # code...
            array_push($arr, $value);
        }
        return $arr;
    }

    protected function getCustomersProps($customer){

        $imgs = json_decode($customer->gallery->images);
        $vids = json_decode($customer->gallery->video);
        $evt = $customer->user->events;

        return [
            "customer" => [
                'id' => $customer->id,
                'user_id' => $customer->user_id,
                'confirmed' => $customer->confirmed,
                'company' => slug_heb($customer->company),
                'businessType' => slug_heb($customer->businessType),
                'title' => $customer->title,
                'contact' => $customer->contact,
                'loggo' => $customer->loggo,
                'email' => $customer->email,
                'descriptions' => $customer->descriptions,
                'content' => $customer->content,
                'address' => $customer->address,
                'tel' => $customer->tel,
                'deals' => $customer->deals,
                'created_at' => Carbon::parse($customer->created_at)->format('Y-m-d H:i:s')
            ],

            "gallery" => [
                'images' => $imgs? (gettype($imgs) == "object")? $this->objectToArray($imgs): $imgs :[],
                'video' => $vids? $vids:[]
            ],
            "events" => $evt
        ];
    }
}