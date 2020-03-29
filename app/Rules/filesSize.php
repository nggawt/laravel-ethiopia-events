<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class filesSize implements Rule
{
    
    public $errorsMsgs = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $values)
    {
        
        $isvalid = true;
        
        $ttl = array_reduce($values, function($total, $current) use($attribute){
            if(! $total) $total = 0;
            $size = filesize($current);
            $total += $size;

            if($this->sizeGratherThen($size)){
                $fileName = $this->getFIleName($current->getClientOriginalName());
                array_push($this->errorsMsgs, "The file name ". $fileName." with size: ".$this->getSize($size)." is too big");
            }
            return $total;
        });

        if($size = $this->sizeGratherThen($ttl)){
            array_push($this->errorsMsgs,"The total files size ".$size." is too big");
            $isvalid = false;
        } 
        return $isvalid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // $msg[':attribute'] = json_encode($this->errorsMsgs);
        return  $this->errorsMsgs;
    }

    protected function getFIleName($item){
        $ex = explode(":", $item);
        return $ex[count($ex) -1];
    }

    private function getSize($size){
        if($size === 0) return false;
        $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($size, 1024));

        $roundedeSize = round($size/pow(1024, $e), 1, PHP_ROUND_HALF_EVEN);
        
        return $roundedeSize . $s[$e];
    }

    private function sizeGratherThen(int $size)
    {
        if($size == 0) return false;
        $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($size, 1024));

        $roundedeSize = round($size/pow(1024, $e), 1, PHP_ROUND_HALF_EVEN);
        $pow = pow(1024, 2) * 6;
        $isBig =  $size > $pow;
        return $isBig? $roundedeSize . $s[$e]:false;
    }
}
