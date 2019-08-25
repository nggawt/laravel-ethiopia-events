<?php

namespace App\Rules;

use App\Customer;
use Illuminate\Contracts\Validation\Rule;

class MinMax implements Rule
{
    public $vlidatedItems = [];
    protected $customer;
    protected $items;
    protected $chosenAttr = "files";
    protected $counter = 0;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($method = "POST", $id, $items) { 
        $this->customer = ($id && $method != "POST")? Customer::findOrFail($id): null; 
        $this->items = $items;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $isValid = true;
        $this->chosenAttr = ($attribute == "files")? "filesToDelete": "files";

        $images = isset($value['images'])? $value['images']: [];
        $video = isset($value['video'])? $value['video']: [];
        $loggo = isset($value['loggo'])? $value['loggo']: [];

        $gals = $this->customer->gallery;
        $imagesDb = json_decode($gals['images'], true);
        $videoDb = json_decode($gals['video'], true);

        $len = count($loggo);
        $itemToDelLoggo = ($attribute == "filesToDelete")? $this->checkFdelExist('loggo', "files"): $this->checkFdelExist('loggo', "filesToDelete");
        $totalCountLoggoVideo = ($attribute == "filesToDelete")? (1 + $len) - $itemToDelLoggo: (1 + $itemToDelLoggo) - $len; 
        $loggoLenValid = $this->itemPassValidation('loggo', $totalCountLoggoVideo, 1, 1);

        if(! $loggoLenValid) {
            $isValid = false;
            ! $loggoLenValid? array_push($this->vlidatedItems,"db: 1 The " .$this->chosenAttr." loggo: ". $itemToDelLoggo ." given: " .$len. " is item not valid ". " total: ". $totalCountLoggoVideo): '';
        }
        

        $len = count($video);
        $itemToDelVideo = ($attribute == "filesToDelete")? $this->checkFdelExist('video', "files"): $this->checkFdelExist('video', "filesToDelete");
        $totalCountLoggoVideo = ($attribute == "filesToDelete")? (1 + $len) - $itemToDelVideo: (1 + $itemToDelVideo) - $len; 
        $videoLenValid = $this->itemPassValidation('video', $totalCountLoggoVideo, 1, 1);

        if(! $videoLenValid)  {
            $isValid = false;
            ! $videoLenValid? array_push($this->vlidatedItems,"db: 1 The " .$this->chosenAttr." video: ". $itemToDelVideo ." given: " .$len. " is item not valid ". " total: ". $totalCountLoggoVideo): '';
        }
        

        $len = count($images);
        $itemToDelImages = ($attribute == "filesToDelete")? $this->checkFdelExist('images', "files"): $this->checkFdelExist('images', "filesToDelete");
        $totalCount = ($attribute == "filesToDelete")? (count($imagesDb) - $len) + $itemToDelImages: (count($imagesDb) + $len) - $itemToDelImages; 
        $imagesLenValid = $this->itemPassValidation('images', $totalCount, 3, 12);
        if(! $imagesLenValid) $isValid = false;
        ! $imagesLenValid? array_push($this->vlidatedItems,"db: ". count($imagesDb) .": The " .$this->chosenAttr." images: ". $itemToDelImages ." given: " .$len. " is item not valid ". " total: ". $totalCount): '';
        
        return $isValid;
    }

    protected function itemPassValidation($attrItem, $total, $min, $max){
        $isImages = $attrItem == "images"? true: false;
        $isValid = $isImages? ($total >= $min && $total <= $max): $total == 1;
 
        return $isValid;
    }

    protected function isValidLens($attrItem, $givenVal, $min, $max, $inDb = 1){

        $itemDelLens = $this->checkFdelExist($attrItem);
        $isImages = $attrItem == "images"? true: false;
        
        ($isImages && $this->chosenAttr == "files")? $totalImages = ($inDb + $itemDelLens - $givenVal): '';
        ! $isImages?  $totalImages = ($inDb + $itemDelLens - $givenVal) : '';

        if( $this->chosenAttr == "filesToDelete"){
            ($isImages)? $totalImages = ($inDb - $itemDelLens + $givenVal) : '';
            ! $isImages?  $totalImages = ($inDb + $itemDelLens - $givenVal): '';
        }

        $itemLensValid = ! $isImages? $totalImages == 1: ($totalImages <= $max && $totalImages >= $min);
        $this->counter++;
        
        return $itemLensValid;
    }

    protected function checkFdelExist($item, $chosenAttr){
        $itemsTodel = isset($this->items[$this->chosenAttr])? $this->items[$this->chosenAttr]: false;
        if($itemsTodel && isset($itemsTodel[$item])) return count($itemsTodel[$item]);
        return 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->vlidatedItems;
    }
}
