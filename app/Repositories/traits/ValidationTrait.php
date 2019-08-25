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
        $formInputs = isset($request['formInputs'])? $filesOb['formInputs'] = json_decode($request['formInputs'], true): false;

        $linksLens = $this->getCombinedKeys($filesOb['files']);
        count($linksLens)? $validators['links'] = Validator::make($linksLens, $this->getLinkLenRules()): '';

        $formInputs? $validators['formInputs'] = Validator::make($filesOb['formInputs'], $this->getInputsRule()):'';
        $files? $validators['files'] = Validator::make($filesOb['files'], $this->getFilesRule()): '';
        
        $validated['errors'] = $this->getValidatorErrors($validators);

        if($validated['errors']){
            $validated['status'] = false;
            $validated['links'] = $linksLens;
            return $validated;
        } 
        return $filesOb;
    }

    private function putValidator($request, $id){
        $filesOb = ['status' => true];
        $validators = [];

        $files = isset($request['files'])? $filesOb['files'] = $this->getFilesParams($request['files']): false;
        $formInputs = isset($request['formInputs'])? $filesOb['formInputs'] = json_decode($request['formInputs'], true): false;
        $filesToDelete = isset($request['filesToDelete'])? $filesOb['filesToDelete'] = json_decode($request['filesToDelete'], true): false;
        
        ($files || $filesToDelete)? $validators['minMax'] = Validator::make([
            'files' => isset($filesOb['files'])? $filesOb['files']: [], 
            'filesToDelete' => isset($filesOb['filesToDelete'])? $filesOb['filesToDelete']: []], [
                // 'files' => [new MinMax($this->method, $id, $filesOb)],
                'filesToDelete' => [new MinMax($this->method, $id, $filesOb)],
        ]): '';

        $linksLens = ($files || $filesToDelete)? $this->linksValidator($files, $filesToDelete): [];

        count($linksLens)? $validators['links'] = Validator::make($linksLens, $this->getLinkLenRules()): '';
        $files? $validators['files'] = Validator::make($filesOb['files'], array_intersect_key($this->getFilesRule($id), $filesOb['files'])): '';
        $formInputs? $validators['formInputs'] = Validator::make($filesOb['formInputs'], array_intersect_key($this->getInputsRule(), $filesOb['formInputs'])):'';
        $filesToDelete? $validators['filesToDelete'] = Validator::make($filesOb['filesToDelete'], array_intersect_key($this->getFileToDelRules($id), $filesOb['filesToDelete'])): '';

        $validated['errors'] = $this->getValidatorErrors($validators);

        if($validated['errors']){
            $validated['status'] = false;
            $validated['links'] = $linksLens;
            $validated['files'] = $files;
            $validated['filesToDelete'] = $filesToDelete;
            $validated['method'] = $this->method;
            return $validated;
        } 
        return $filesOb;
    }

    private function linksValidator($linksFile, $linksFilesDel){

        $linksLensFi = $linksFile? $this->getCombinedKeys($linksFile): [];
        $linksLensFd = $linksFilesDel? array_reduce($linksFilesDel, function($total, $item){
             if(! $total) $total = [];
            $val = array_values($item)[0];
            $total[$val] = $val;
            return $total;
        }): [];

        $resault  = array_merge($linksLensFi, $linksLensFd);
        return $resault;
    }

    private function getValidatorErrors($validator){

        return array_reduce($validator, function($total, $current){
            if(! isset($total)) $total = [];
            $currentFailErrors = $current->fails()? $current->errors()->all(): false;
            ($currentFailErrors)? $total = array_merge($total, $currentFailErrors):$total = $total;
            return $total;
        });
    }

    private function getCombinedKeys($items){

        return array_reduce($items, function($totalItems, $item){
                            if(! $totalItems) $totalItems = [];
                            $key = array_keys($item);
                            $value = array_values($key);
                            $item = array_combine($key, $value);
                            $totalItems = array_merge($totalItems, $item);
                            return $totalItems; 
                        });
    }

    private function getFilesRule($id){
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
            '*' => "required|string|min:12|max:90|not_regex:/.*[\@\+\%\*\$\<\>\(\)]+.*$/ims",
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
            'descriptions' => "requiredstring|min:6|max:255",
            'content' => "required|string|min:12",
            'deals' => "required|string|min:3",
            'confirmed' => "boolean"
        ];
    }
}