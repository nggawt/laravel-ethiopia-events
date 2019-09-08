<?php

namespace App\Repositories\traits;

use App\Repositories\traits\FilesProcess;
use App\Rules\FileExist;
use App\Rules\MinMax;
use App\Rules\filesSize;
use Illuminate\Support\Facades\Validator;

trait ValidationTrait {

    use FilesProcess;
    private $method = "POST";

    public function validateResponse($request, $method = "POST", $id = false){

        $this->method = $method;
        if($this->method == "POST"){
            return $this->postValidator($request);
        }
        return $this->putValidator($request, $id);
    }

    private function postValidator($request){
        $filesOb = ['status' => true];
        $validators = [];

        $files = isset($request['files'])? $filesOb['files'] = $this->getFilesParams($request['files']): false;
        $formInputs = isset($request['formInputs'])? $filesOb['formInputs'] = $this->decodIfIsJson($request['formInputs']): false;

        $validatorHelper = $this->validateItems($filesOb);
        // return $validatorHelper;
        $validated['errors'] = $this->getValidatorErrors($validatorHelper);

        if($validated['errors']){
            $validated['status'] = false;
            $validated['links'] = $linksLens;
            $validated['files'] = $files;
            $validated['method'] = $this->method;
            return $validated;
        } 
        return $filesOb;
    }

    private function decodIfIsJson($item){
        return is_array($item)? $item: json_decode($item, true);
    }

    private function putValidator($request, $id){
        $filesOb = [];
        $validators = [];

        $files = isset($request['files'])? $filesOb['files'] = $this->getFilesParams($request['files']): false;
        $formInputs = isset($request['formInputs'])? $filesOb['formInputs'] = $this->decodIfIsJson($request['formInputs']): false;
        $filesToDelete = isset($request['filesToDelete'])? $filesOb['filesToDelete'] = $this->decodIfIsJson($request['filesToDelete']): false;

        $validatorHelper = $this->validateItems($filesOb, $id);
        // return $validatorHelper;
        $validated['errors'] = $this->getValidatorErrors($validatorHelper);

        if($validated['errors']){
            $validated['status'] = false;
            $validated['files'] = $files;
            $validated['filesToDelete'] = $filesToDelete;
            $validated['method'] = $this->method;
            return $validated;
        } 
        $filesOb['status'] = true;
        return $filesOb;
    }

    private function validateItems($items, $id = false){

        return $this->itemsHelper($items, function($item, $keyItem) use($items, $id){

            switch ($keyItem) {
                case 'files':
                
                    $fDel =  isset($items['filesToDelete'])? $items['filesToDelete']: false;
                    return $this->validateFilesToUpdate($item, $keyItem, $id, $fDel);
                break;
                case 'formInputs':
                    $validators['formInputs'] = ($this->method == "POST")? Validator::make($item, $this->getInputsRule()): 
                                                    Validator::make($item, array_intersect_key($this->getInputsRule(), $item));
                    return $validators;

                break;
                case 'filesToDelete':
                
                    $files = isset($items['files'])? $items['files']: false;
                    return $this->validateFilesToDel($item, $keyItem, $id, $files);
                break;

                default:
                    return false;
                break;
            }
            
        }, true);
    }

    private function validateFilesToUpdate($currentItem, $keyItem, $id, $itemsDel){

        if($this->method == "POST"){
            $validators['files'] = Validator::make($currentItem, $this->getFilesRule());
        }else{
            $rulesUp = array_intersect_key($this->getFilesRule($id), $currentItem);
            $validators['files'] = Validator::make($currentItem, $rulesUp);

            $validators['minMaxUp'] = Validator::make([$keyItem => $currentItem], [
                $keyItem => [new MinMax($this->method, $id, $itemsDel)],
            ]);
        }
        $linksLens = $this->combineKeyValue($currentItem, "keys");
        $validators['linksUp'] = Validator::make($linksLens, $this->getLinkLenRules());

        return $validators;
    }

    private function validateFilesToDel($currentItem, $keyItem, $id, $itemsUp){

        $rulesDel = array_intersect_key($this->getFileToDelRules($id), $currentItem);
        $validators['filesToDelete'] = $rulesDel? Validator::make($currentItem, $rulesDel): [];

        $linksLens = $this->combineKeyValue($currentItem);
        $validators['linksDel'] = Validator::make($linksLens, $this->getLinkLenRules());

        $validators['minMaxDel'] = Validator::make([$keyItem => $currentItem], [
            $keyItem => [new MinMax($this->method, $id, $itemsUp)],
        ]);
        return $validators;
    }

    private function getValidatorErrors($validator){

        return $this->itemsHelper($validator, function($current){
            $currentFailErrors = (is_object($current) && $current->fails())? $current->errors()->all(): false;
            return $currentFailErrors;
        });
    }

    private function combineKeyValue($items, $with = ""){
        // transform array the keys with keys and value with keys
        // transform array the keys with value and value value
        return $this->itemsHelper($items, function($current) use($with){
            $keys = ($with == "keys")? array_keys($current): $current;
            $item = array_combine($keys, $keys);
            return $item;
        });
    }

    private function getFilesRule($id = false){
        return [
            'images' => ['required', 'array', new FileExist($this->method, $id), new filesSize],
            'images.*' => "required|file|between:10,4000|image|mimes:jpeg,jpg,png",
            'loggo' => ['required', 'array', 'min:1|max:1', new FileExist($this->method, $id), new filesSize],
            'loggo.*' => "required|file|between:10,4000|image|mimes:jpeg,jpg,png",
            'video' => ['required', 'array', 'min:1|max:1', new FileExist($this->method, $id), new filesSize],
            'video.*' => "required|file|between:200,6000|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime"
        ];
    }

    private function getFileToDelRules($id){

        return [
            'images' => ['array','between:1,9', new FileExist($this->method, $id)],
            'images.*' => "required|string|min:12|max:90",
            'loggo' => ['array','min:1', 'max:1', new FileExist($this->method, $id)],
            'loggo.*' => "required|string|min:12|max:90",
            'video' => ['array','min:1', 'max:1', new FileExist($this->method, $id)],
            'video.*' => "required|string|min:12|max:90",
        ];
    }

    private function getLinkLenRules(){

        return [
            '*' => "required|string|min:10|max:120|not_regex:/.*[\@\+\%\*\$\<\>\(\)]+.*$/ims",
        ];
    }

    private function getInputsRule(){
        return [
            'company' => "required|string|min:3",
            'owner' => "numeric",
            'businessType' => "required|string|min:3|max:30",
            'title' => "required|string|min:4|max:255",
            'contact' => 'required|string|min:3|max:30',
            'email' => "required|string|email|unique:customers,email",
            'tel' => "required|string|min:8|max:10",
            'address' => "required|string|min:3|max:120",
            'content' => "required|string|min:12",
            'deals' => "required|string|min:3",
            'confirmed' => "boolean"
        ];
            // 'descriptions' => "requiredstring|min:6|max:255",
    }
}