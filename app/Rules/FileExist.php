<?php

namespace App\Rules;

use App\Customer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class FileExist implements Rule
    {
    protected $baseUrl = "./assets/pages/customers/";
    public $vlidatedItems = [];
    protected $isFileType = false;
    protected $customer;
    private $method;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($method = "POST", $id) { 
        $this->customer = ($id && $method != "POST")? Customer::findOrFail($id): null; 
        $this->method = $method;
    }

/**
 * Determine if the validation rule passes.
 *
 * @param  string  $attribute
 * @param  mixed  $value
 * @return bool
 */
    public function passes($attribute, $fileItems)
    {
        
        // $attribute = video, images, loggo, filesToDelete
        // $fileItems = {}[], string[]

        // file exist
        // check if the $value is object || string
        // create links path of file directory and db
        // check if the file exist with given link, in directory and db
        // set error messages
        // return true or false with errors  msgs response
        $valid = true;
        foreach($fileItems as $key => $value) {

            $links = $this->getLinks($value);

            $inDir = $this->fileExist($links['dir']);

            if($this->method != "POST"){//  && gettype($value) != "object"

                $inDb = $this->linkInDb($attribute, $links['db']);
                if(gettype($value) == "object"){
                    if($inDb){
                        $valid  = false;
                        array_push($this->vlidatedItems, "The " .$attribute .": ". $links['db'] . " must not be exist in db.");
                    }
                    if($inDir){
                        $valid  = false;
                        array_push($this->vlidatedItems, "The " .$attribute ." in: ". $links['dir'] . " link must not be exist in directory. " . $inDb);
                    }
                }else{
                    if(! $inDb){
                        $valid  = false;
                        array_push($this->vlidatedItems, "The " .$attribute .": ". $links['db'] . " shuld be exist in db.");
                    }
                    if(! $inDir){
                        $valid  = false;
                        array_push($this->vlidatedItems, "The " .$attribute ." in: ". $links['dir'] . " link shuld be exist in directory. " . $inDb);
                    }
                }

            }else if($this->method == "POST" && $inDir){
                $valid  = false;
                array_push($this->vlidatedItems, "The " .$attribute ." in: ". $links['dir'] . " link is exist in directory. " . $inDb);
            }
        }
        return $valid;  
    }

    /**
    * Get the validation error message.
    *
    * @return string
    */
    public function message()
    {
        return $this->vlidatedItems;//['message' => 'The validation error message.', 'request items' => $this->requestItems];
    }

    protected function linkInDb($attribute, $link){

        if($attribute == "loggo") return ($this->customer->loggo == $link);

        $gals = $this->customer->gallery;
        if($attribute == "video"){
            $video = json_decode($gals['video'], true);
            return in_array($link, $video);
        }else if ($attribute == "images"){
            $images = json_decode($gals['images'], true);
            return in_array($link, $images);
        } 
    }
    protected function getLinks($item){

        if(gettype($item) == "string") return  $this->createLinks($item);

        if(gettype($item) == "object"){
            $this->isFileType = true;
            $fileName = $item->getClientOriginalName();
            $exPlode = explode(':', $fileName);
            $link = $this->getFullPath($exPlode).".".($item)->extension();

            return $this->createLinks($link);
        }
    }

    protected function createLinks($link){
        $links = [];
        $isFullPath = strpos($link, $this->baseUrl) !== false;
        $links['db'] = $isFullPath? $link: $this->baseUrl.$link;
        $links['dir'] = $isFullPath? str_replace($this->baseUrl, '', $link): $link;
        return $links;
    }

    protected function getFullPath($paths){
        $path;
        foreach ($paths as $key => $value) {
            if($key == 0) continue;
            (isset($path))? $path .= '/'.$value: $path = $value;
        }
        $pos = strpos($path, '/');
        if($pos === 0) substr_replace($path,'',1,1);
        return $path;
    }

    protected function fileExist($link, $file = false){

        $result =  $file? (Storage::disk('customers')->exists($link) && $file->isValid()): Storage::disk('customers')->exists($link);
        return $result;
    }
    }