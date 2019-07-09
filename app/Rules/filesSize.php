<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class filesSize implements Rule
{
    public $val;
    public $errorsMsgs = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $values)
    {
        $this->val = $values;
         $size = 0;
        
        
        foreach ($values as $key => $value) {
            $size += filesize($value['file']);
            $type = filetype($value['file']); 
            $mimetype =['image/jpg', 'image/png'];

            if($sz = $this->sizeGratherThen($size)) {
                $msg = $value['name'] .' is invalid. '. $sz;
                array_push($this->errorsMsgs, $msg);
            }

            if($type != "file") {
                $msg = $value['name'] .' is invalid. '. $type;
                array_push($this->errorsMsgs, $msg);
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $info = finfo_file($finfo, $value['file']);
            
            if(! in_array($info, $mimetype)) {
                $msg = $value['name'] .' mime type is invalid. '. $info;
                array_push($this->errorsMsgs, $msg);
            }
            finfo_close($finfo);
        }
        if(empty($this->errorsMsgs)) return true;
        // if(count($this->errorsMsgs)) $fail($this->errorsMsgs);
        //return false;//! is_array($value);
        // function ($attribute, $values, $fail) {
                   
        //         },
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'The validation :attribute error message.';
        return $this->errorsMsgs;
    }

    private function sizeGratherThen($size)
    {
        
        $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($size, 1024));

        $roundedeSize = round($size/pow(1024, $e), 1, PHP_ROUND_HALF_EVEN);
        $pow = pow(1024, 2) * 6;
        $isBig =  $size > $pow;
        return $isBig? $roundedeSize . $s[$e]:false;
    }
}
