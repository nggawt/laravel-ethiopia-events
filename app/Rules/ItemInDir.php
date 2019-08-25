<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ItemInDir implements Rule
{
    protected $dataUrl = "./assets/pages/customers";
    public $vlidatedItems = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $Filesvalues)
    {
        // $reqName = $value->getClientOriginalName();
        $valid = true;
        foreach ($Filesvalues as $key => $value) {

            $reqName = $value->getClientOriginalName();
            $exPlode = explode(':', $reqName);

            $pathToFile = $this->getFullPath($exPlode).".".($value)->extension();
            $path = str_replace(['images', 'jpeg'], ['gallery', 'jpg'], $pathToFile);
            $vlidatedItems = $this->fileExist($path);

            if($vlidatedItems){
                $valid  = false;
                array_push($this->vlidatedItems, $attribute .": ". $path . " file exisst.");
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


    protected function getFullPath($paths){
        $path;
        foreach ($paths as $key => $value) {
            if($key == 0) continue;
            (isset($path))? $path .= '/'.$value: $path = $value;
        }
        return $path;
    }

    protected function fileExist($link, $file = false){

        $result =  $file? (Storage::disk('customers')->exists($link) && $file->isValid()): Storage::disk('customers')->exists($link);
        return $result;
    }
}
