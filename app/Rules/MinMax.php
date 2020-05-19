<?php

namespace App\Rules;

use App\Customer;
use Illuminate\Contracts\Validation\Rule;

class MinMax implements Rule
{
    public $vlidatedItems = [];
    protected $customer;
    protected $items;
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

        $gals = $this->customer->gallery;

        $keys = array_keys($value);
        return array_reduce($keys, function($totalItems, $currentItem) use($value, $gals, $attribute){
            $isFilesToDel = ($attribute == "filesToDelete");
            $isImage = ($currentItem == "images");

            $countGiven = $isFilesToDel?  -(count($value[$currentItem])): count($value[$currentItem]);
            $itemsDb = $isImage? count(json_decode($gals[$currentItem], true)): 1;

            $countItems = $isFilesToDel? $this->getItemsCounts($currentItem): -($this->getItemsCounts($currentItem));
            $culcTotal = $itemsDb + $countGiven + $countItems;

            $itemIsValid = $isImage? $culcTotal >= 3 && $culcTotal <= 12: $culcTotal == 1;
            if(! $itemIsValid){
                $totalItems = false;

                $msg = "The ". $currentItem
                . " with TOTAL items Of: "
                . $culcTotal. " with GIVEN: ". $countItems
                . " with given ".$attribute." ITEMS: "
                . $countGiven ." is invalid.";

                array_push($this->vlidatedItems, $msg);
            }
            return $totalItems;

        }, true);
    }

    protected function itemPassValidation($attrItem, $total, $min, $max){
        $isValid = ($attrItem == "images")? ($total >= $min && $total <= $max): $total == 1;
        return $isValid;
    }

    protected function getItemsCounts($item){
        if($this->items && isset($this->items[$item])) return count($this->items[$item]);
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
