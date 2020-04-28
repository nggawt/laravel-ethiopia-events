<?php

namespace App\Repositories\Customer;


use App\Customer;
use App\Gallery;
use App\Repo\traits\Messages;
use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Str;

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
     * Get's all articles.
     *
     * @return mixed
     */
    public function all(){

    	return response()->json($this->transformCustomers(), 200);
    }

    public function create(array $customerProps){

        $inputs = $customerProps['formInputs'];
        $files = $customerProps['files'];

        $dowed = $this->fnLooper($files, "downloadFiles");

        if(! $dowed) return ['message' => "unknown error"];

        $inputs['user_id'] = auth('api')->user()? auth('api')->user()->id: auth('admin')->user()->id;
        $inputs['confirmed'] = $inputs['confirmed']? 1: 0;
        $inputs['slug'] = Str::slug($inputs['title']);
        $inputs['loggo'] = $dowed['loggo'][0];

        $customer = $this->customer->create($inputs);
        $gal = [
            "customer_id" => $customer->id,
            "images" => json_encode($dowed['images']),
            "video" => json_encode($dowed['video'])
        ];
        $gal = Gallery::create($gal);
        
        return [
            'downloaded' => $dowed,
            'gallery' => $gal,
            'inputs' => $inputs,
            'customer created' => $customer
        ];
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update(array $items, $id){

        $fuploaded = isset($items['files'])? $items['files']: [];
        $inputs = isset($items['formInputs'])? collect($items['formInputs'])->except(['businessType', 'company'])->toArray(): [];
        $fTodelete = isset($items['filesToDelete'])? $items['filesToDelete']: [];
        $customer = $this->customer->findOrfail($id);
        
        isset($inputs['title'])? $inputs['slug'] = Str::slug_heb($inputs['title']): '';
        Arr::has($inputs, 'confirmed')? $inputs['confirmed'] = $inputs['confirmed']? 1: 0: '';

        // download files if fail return fail
        $fdownloaded = $this->fnLooper($fuploaded, "downloadFiles");
        if(! $fdownloaded) return ['message' => "unknown error"];
        // delete files
        $deleted = $this->fnLooper($fTodelete, 'deleteFromStorage');
        // update files
        $gal = $this->upgallery($customer, $fdownloaded, $deleted);
        // update customer
        $sycEmail = isset($inputs['email'])? $this->syncUserEmail($customer, $inputs['email']): true;
        if(! $sycEmail) return ['message' => "Email was taken ".$inputs['email'] ];
        (count($inputs))? $customer->update($inputs): '';

        return [
            'downloaded' => $fdownloaded,
            'gallery' => $gal,
            'inputs' => $inputs,
            'deleted' => $deleted
        ];
    }

    /**
     * Deletes a article.
     *
     * @param int
     */
    public function delete($id){

    }

    protected function fnLooper($files, $fn){
        // loop over images, video, loggo objects:  {}{}[] | {}string[]
        // 
        $dowed = collect($files)->map(function($value, $key) use($fn){
            $download = $this->looper($key, $value, $fn)[$key];
            return in_array(false, $download)? false :$download;
        });

        $pass = $dowed->every(function($value, $key){
            return $value !== false;
        });
        return $pass? $dowed: $pass;
    }

    protected function syncUserEmail($customer, $requestEmail){

        $userEmailTeken = User::where('email', $requestEmail)->first();
        if(empty($userEmailTeken) || is_null($userEmailTeken)){
            $customer->user->update(['email' => $requestEmail]);
            return true;
        }else{
            return false;
        }
    }

    protected function upgallery($customer, $fu, $fd){
        $customerGallery = $customer->gallery;
        $images = json_decode($customerGallery->images, true);
        $video = json_decode($customerGallery->video, true);
        $gal = [];

        $downloadedImages = isset($fu['images'])? $fu['images']: false;
        if(isset($fd['images']) || $downloadedImages) {

            $fd = isset($fd['images'])? $fd['images']: [];
            $mergedImages = $downloadedImages? array_merge($images, $downloadedImages): $images;
            $diff =  array_diff($mergedImages, $fd);
            $gal["images"] = json_encode($diff);
        };

        if(isset($fd['video'])) {
            $upvideo = isset($fu['video'])? $fu['video']: false;
            $mergedvideo = $upvideo? array_merge($video, $upvideo): $video;
            $diff =  array_diff($mergedvideo, $fd['video']);
            $gal["video"] = json_encode($diff);
        };

        if(isset($fd['loggo'])) {
            $customer->loggo = $fu['loggo'][0];
            $customer->save();
        };
        count($gal)? $customerGallery->update($gal): '';
        return $gal;
    }

    protected function deleteFromStorage($target, $key, $link, $nameFlag = false){ // $target, string $link = ''

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

    protected function looper($target, $files, $fn){
        // loop over objects:  {}[] | string[]
        // call fn to individual item in array
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

            $businessType = Str::slug_heb($customer->businessType);
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
        $vids = json_decode($customer->gallery->videos);

        // $evt = $customer->user->events;

        return [
            "customer" => [
                'id' => $customer->id,
                'user_id' => $customer->user_id,
                'confirmed' => $customer->confirmed,
                'company' => $customer->company,
                'businessType' => Str::slug_heb($customer->businessType),
                'title' => $customer->title,
                'contact' => $customer->contact,
                'loggo' => $customer->loggo,
                'email' => $customer->email,
                'content' => $customer->content,
                'address' => $customer->address,
                'tel' => $customer->tel,
                'deals' => $customer->deals,
                'created_at' => Carbon::parse($customer->created_at)->format('Y-m-d H:i:s'),
                // "forbidden" => $customer->user->forbidden
            ],

            "gallery" => [
                'images' => $imgs? (gettype($imgs) == "object")? $this->objectToArray($imgs): $imgs :[],
                'video' => $vids? $vids:[]
            ],
            // "events" => $evt
        ];
    }
}