<?php

namespace App\Http\Responses;

use App\Repositories\traits\FilesProsece;
use App\Rules\FileExist;
use App\Rules\filesSize;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Validator;

/**
 * customer validation responsable
 */
class CustomerValidationResponse implements Responsable
{
	use FilesProsece;

	protected $formRoles = [ 
		'formInputs' => array(
			'company' => "required|string|min:3",
			'owner' => "numeric",
			'businessType' => "required|string|min:3",
			'title' => "required|string|min:4",
			'contact' => 'required|string|min:3',
			'email' => "required|string|email|unique:customers,email",
			'tel' => "required|string|numeric|min:8",
			'address' => "required|string|min:4",
			'descriptions' => "string|min:12",
			'content' => "required|string|min:12",
			'deals' => "required|string|min:3",
			'confirmed' => "boolean"
		),
		'images' => "required|file|between:10,4000|image|mimes:jpeg,jpg,png",
		'video' => "required|file|between:200,6000|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime"
	];

	public function __construct() { }

	public function toResponse($request){
	// formInputs, files
	// $formInputs = json_decode($request->formInputs, true);
		$filesOb = [
			'files' => $this->getFilesParams($request['files']),
			'formInputs' => json_decode($request->formInputs, true),
			'status' => true
		];

		$validator1 = Validator::make($filesOb['formInputs'], $this->formRoles['formInputs']);
		$validator2 = Validator::make($filesOb['files'], [
			'images' =>['required', 'array','between:3,12', new FileExist, new filesSize],
			'images.*' => $this->formRoles['images'],
			'loggo' => ['required', 'array', 'min:1|max:1', new FileExist, new filesSize],
			'loggo.*' => $this->formRoles['images'],
			'video' => ['required', 'array', 'min:1|max:1', new FileExist, new filesSize],
			'video.*' => $this->formRoles['video'],
		]);

		$res = [
			'validator' => $validator1->fails() || $validator2->fails() ? $this->getValidatorConcatMessages($validator1, $validator2):false, 
			'status' => false
		];
		return $res['validator']? true: false;
	}

	protected function getValidatorConcatMessages($validator1, $validator2){

		if($validator1->fails() && $validator2->fails()){
			return collect($validator1->errors()->all())->concat($validator2->errors()->all());
		}else if($validator1->fails()){
			return $validator1->errors()->all();
		}
		return $validator2->errors()->all();
	}
}