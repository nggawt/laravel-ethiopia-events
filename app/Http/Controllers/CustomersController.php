<?php

namespace App\Http\Controllers;

use App\Http\Responses\CustomerValidationResponse; 
use App\Repositories\Customer\CustomerRepoInterface;
use App\Repositories\traits\ValidationTrait; 
use Illuminate\Http\Request;

class CustomersController extends Controller
{

    use ValidationTrait;
    
    protected $dataUrl = "./assets/pages/customers/";

    protected  $convetedMasseges = [
       "company" => "שם חברה",
       "businessType" => "סוג העסק",
       "title" => "כותרת",
       "contact" => "איש קשר",
       "content" => "תוכן",
       "email" => "אימייל",
       "tel" => "פלאפון או טלפון",
       "address" => "כתובת",
       "descriptions" => "אודות",
       "deals" => "מבצעים",
       "owner" => "משתמש  ",
       "confirmed" => "סטטוס "
   ];

   protected $repo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepoInterface $repo)
    {
        // $this->middleware('cors');
        $this->middleware('auth:api', ['except' => ['index', 'store', 'update']]);
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        // $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
        
        $this->repo = $repo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repo->all();
    }


    public function show($id)
    {
       return response()->json($this->repo->findOrFail($id),200);
   }

   public function store(Request $request){
    $requestAll = $request->all();
    // if(! auth('api')->check() || ! auth('admin')->check()) return response()->json(['error' => 'Unauthorized'], 401);
    if(! $request->file('files') || ! $requestAll->formInputs) return response()->json(['error' => 'No Data Sented'], 200);
    $validateItems =   $this->validateResponse($requestAll, $request->method());
    return $validateItems['status']? $this->repo->create($validateItems): $validateItems;
}

public function update(Request $request, $id){

    $requestAll = $request->all();
    if(! $request->file('files') && ! $request->formInputs & ! $request->filesToDelete) return response()->json(['error' => 'No Data Sented'], 200);
    $validateItems =   $this->validateResponse($requestAll, $request->method(), $id);
    // return $validateItems;
    return $validateItems['status']? $this->repo->update($validateItems, $id): $validateItems;

    /******* get and send back messages ******/
    return response()->json(['message' => "customer Successfully updated!",$this->customers->getMessages()],200);
}

protected function updateUserEmail($customer, $requestEmail){

    $userEmailTeken = User::where('email', $requestEmail)->first();
    if(empty($userEmailTeken)){
        $customer->user->update(['email' => $requestEmail]);
    }else{
        return response()->json(['errors' => [ "email"=> array("האימייל כבר קיים במערכת שלנו.")]],200);
    }
}

protected function gallUpdate($customer, $down){
    if(isset($down['image']) && count($down['image'])) {
        $customer->gallery['image'] = json_encode($down['image']);
        $customer->gallery->save();
    }
    if(isset($down['video']) && count($down['video'])) {
        $customer->gallery['video'] = json_encode($down['video']);
        $customer->gallery->save();
    }
    if(isset($down['loggo']) && count($down['loggo'])) {
        $loggo = $down['loggo'][0];
        $customer->loggo = $loggo;
        $customer->save();
    }
}

public function destroy(Request $request,Customer $customer){

    return ["request" => $request->all(), "customer" => $customer];
    $imgs = Customer::find($id)->gallery->image;
    $imgs = json_decode($imgs, true);
    $fd = json_decode($request['filesToDelete'], true);

    $delFiles = $this->customers->delFromGal($imgs, $fd);
    return ['success' => $delFiles['success']];

}

/******* custom validation *******/

    #rquest is store validation state
        # have only data to store

    # request is update validation state
        # have to update and to delete
        # have to update but no to delete
        # have to delete but no to update
        # have no update and delete

    # validations attributes
    # minFiles
        # min files allowed
    # maxFiles
        # max files allowed
    # min-max filesCahrLen
        # chack min Or max files names length allowed
    # charTypes
        # disallow char types of file name
    # unknownError
        # catch errors and return unexcepted errors
    # fixErrors
        # stop excution until fix errors
    # fileSize
        # file too big Or total files sizes is too big
    # filesUnChange
        # return files requeted unchanged
    # filesExisst
        # file exisst in diroctry or database

/**** validate min-maxFile example when request is update ****/
    # have to update and to delete
        # have to update more than allowed
        # have to delete more than allowed
        # have to update and to delete more than allowed
    # have to update but no to delete
        # have to updat more than allowed
    # have to delete but no to update
        # have to delete more than allowed
    # have no update and delete

private function mainValidation(array $files = [], string $requestMethod){


    $isValid = true;
    $methodIsUpdate = ($requestMethod == "PUT" || $requestMethod == "PATCH");
    $upFiles = $methodIsUpdate? $files['update']: $files['store'];
    $customer = [];
    $toDelFiles = [];
        // $upFiles = isset($files['update'])? $files['update']:($requestMethod == "POST" && isset($files['store']))? $files['store']: [];

        if($methodIsUpdate){// $requestMethod
            $toDelFiles = isset($files['delete'])? $files['delete']: [];
            $customer = isset($files['customer'])? $files['customer']: [];
        }
        
        // validate uploaded files
        $items = $this->maper($upFiles, 'getValidationProps', $customer);
        $validateUploadedFiles = $this->validateUploadedFiles($items);
        if(! $validateUploadedFiles) $isValid  = false;

        # validate files to delete exisst
        # files to deleted must be exist in dir and db
        # files uploaded must no exist in dir and db

        $upFilesKeys = isset($upFiles) && count($upFiles)? true:false;
        $delFilesKeys = isset($toDelFiles) && count($toDelFiles)? true:false;


        $map['del'] = $delFilesKeys? $this->maper($toDelFiles, 'isExist', $customer, 'delete'): [];
        $map['up'] = $upFilesKeys? $this->maper($upFiles, 'isExist', $customer, 'uploade'): [];
        
        // $this->customers->setMessages('errors', 'TEST357', $map);
        $statusDel = array_column($map['del'], 'status');
        $statusUp = array_column($map['up'], 'status');

        # before production need change status variable conditions to not oparator;
        $exist = in_array(false, $statusUp) || in_array(false, $statusDel)? false: true;//in_array(false, $statusDel) && 
        
        /**** validate min-maxFile ****/
        $minMaxfiles = $this->validateMinMaxFiles($upFiles, $toDelFiles, $customer);
        $minMaxStatus = $minMaxfiles['status'] && $requestMethod == "POST"? $minMaxfiles['allow_store']: $minMaxfiles['status'];
        (! $minMaxStatus)? $this->customers->setMessages('errors', 'minMaxFile', $minMaxfiles): "";

        (! $validateUploadedFiles || ! $exist || ! $minMaxStatus)? $isValid = false: '';
        // $this->customers->setMessages('errors', "TEST379isValid", ['isValid' => $isValid]);
        
        return $isValid;
    }

    protected function isExist($target, $items, $exisstedFiles, string $typeFiles = ""){
        $arr = ['status' => true];

        foreach ($items as $key => $value) {

            $item = isset($value['fullPath'])? $value['fullPath'].".".$value['ext']: $value;
            $file = isset($value['file'])? $value['file']: false;

            $fileName = explode('/', $item)[count(explode('/', $item)) - 1];

            $itemDir = (string) $item;
            if(strpos($item, './assets/pages/customers/') !== false){
                $itemDir = (string) explode('./assets/pages/customers/', $item)[1];
            }

            $dir = $this->customers->fileExist($itemDir, $file);
            $db = in_array($item, $exisstedFiles);

            $arr[$fileName] = ['db' => $db, 'dir' => $dir];

            if($typeFiles == 'delete' && (! $db || ! $dir)){ 
                $msgNotExist = ['file_not_Exist' => $fileName . " ". "הקובץ לא קיים במערכת. " . $typeFiles . " " .$value];
                $this->customers->setMessages('errors', $target, $msgNotExist);
                $arr['status'] = false;
            }
            if($typeFiles == 'uploade' && ($db || $dir)){ 
                $msgExist = ['file_Exist' => $fileName . " ". "קיים במערכת."];
                $arr['status'] = false;
                $this->customers->setMessages('errors', $target, $msgExist);
            }
        }
        return $arr;
    }

    protected function validateUploadedFiles($items){
        $validateFils = [];
        $validateFilsNames = [];
        $filesSize = 0;

        if($items && count($items)){

            (isset($items['files']['items']))? $validateFils['items'] = $items['files']['items']: "";
            (isset($items['files']['ruls']))? $validateFils['ruls'] = $items['files']['ruls']: "";

            (isset($items['names']['items']))? $validateFilsNames['items'] = $items['names']['items']: "";
            (isset($items['names']['ruls']))? $validateFilsNames['ruls'] = $items['names']['ruls']: "";

            (isset($items['fileSize']))? $filesSize = $filesSize + $items['fileSize']: "";
        }

        /**** validate total filesSizes ****/
        if($sizeEx = $filesSize? $this->sizeGratherThen($filesSize): 0){
            $message = array('fileSize' => 'נפח הקבצים גדול מידי : ' . $sizeEx);
            $this->customers->setMessages('errors', 'files', $message);
            $isValid = false;
        }

        # validate required ,fileSize ,fileType ,mimeType
        $fileIsValid = count($validateFils)? $this->validateItems($validateFils['items'], $validateFils['ruls']): true;
        
        # validate filesMinMaxCharLen
        $filesCharLenIsValid = count($validateFilsNames)? $this->validateItems($validateFilsNames['items'], $validateFilsNames['ruls']): true;

        return ($fileIsValid && $filesCharLenIsValid)? true: false;
    }

    protected function maper(array $files = [], $fn, $customer, string $typeFiles = ""){
        $isCustomer = $customer && $customer->id? true:false;
        $galItems = [ 
            "images" => $isCustomer? json_decode($customer->gallery->image, true):[],
            "video" => $isCustomer? json_decode($customer->gallery->video, true):[],
            "loggo" => $isCustomer? array($customer->loggo):[]
        ];

        $maperOb = [];
        foreach ($files as $key => $value) {

            count($value)? $maperOb[$key] = $this->$fn($key, $value, $galItems[$key], $typeFiles): '';
        }
        return $maperOb;
    }

    protected function getValidationProps(string $target, array $items = []){

        $data = [
            'fileSize' => 0
        ];

        foreach ($items as $key => $value) {

            $file = (object) $value['file'];
            $target = (string) $value['target'];
            $fullPath =  (string) $value['fullPath'];
            
            $data['files']['items'][$fullPath] = $file;
            $data['files']['ruls'][$fullPath] = $this->filesRules[$target];

            $data['names']['items'][$fullPath] = $fullPath;
            $data['names']['ruls'][$fullPath] = $this->conFilesAttr[$target]['ruls'];

            $data['fileSize'] = $data['fileSize'] + filesize($file);
        }
        return $data;
    }

    private function validateMinMaxFiles(array $upFiles = [], array $toDelFiles = [], $customer = []){

        $validationsItems = ['images', 'video', 'loggo'];
        
        $isCustomer = $customer && $customer->id? true:false;
        $galItems = [ 
            "images" => $isCustomer? json_decode($customer->gallery->image, true):[],
            "video" => $isCustomer? json_decode($customer->gallery->video, true):[],
            "loggo" => $isCustomer? array($customer->loggo):[]
        ];

        $filesMaper = array_map(function($target) use($upFiles, $toDelFiles, $galItems){
            $cUpfiles = isset($upFiles[$target]) && count($upFiles[$target])? $upFiles[$target]: [];
            $cDelFiles = isset($toDelFiles[$target]) && count($toDelFiles[$target])? $toDelFiles[$target]: [];

            $minMax = ($cUpfiles || $cDelFiles)? $this->minMaxFiles($target, $cUpfiles, $cDelFiles, $galItems[$target]): false;
            (! $minMax)? $minMax['allow_store'] = false:"";

            return $minMax;

        }, $validationsItems);

        $columnStatus = array_column($filesMaper, "status");
        $columnStore = array_column($filesMaper, "allow_store");

        $filesMaper = collect($filesMaper)->collapse();

        $filesMaper['status'] = in_array(false, $columnStatus)? false: true;
        $filesMaper['allow_store'] = !$filesMaper['status'] || in_array(false, $columnStore)? false: true;

        return $filesMaper;
    }

    protected function minMaxFiles($target, array $upFiles = [], array $delFiles = [], array $galFiles = []){

        $isLoggoOrVideo = (($target == "loggo") || ($target == "video"))? true:false;
        $convertedMsg = false;
        $isValid = true;
        $countedUpfiles = count($upFiles);
        $countedDelfiles = count($delFiles);
        $countedGalfiles = count($galFiles);
        $msgs = [];

        if($isLoggoOrVideo){

            if($countedGalfiles && (($countedUpfiles && ! $countedDelfiles) || (! $countedUpfiles && $countedDelfiles) || ($countedDelfiles > 1))){
                $convertedMsg = $this->conFilesAttr[$target]["name"] 
                . ": חייב להכיל קובץ " 
                .$this->conFilesAttr[$target]["type"] . " " .$this->conFilesAttr[$target]['minMaxFiles']["min"] 
                . " לפחות";
                $isValid = false;
            }else if(($countedUpfiles > 1)){
                $convertedMsg = $this->conFilesAttr[$target]["name"] 
                . ": יכול להכיל " 
                . $this->conFilesAttr[$target]['minMaxFiles']["max"]
                ." קבצי "
                .$this->conFilesAttr[$target]["type"]  
                . " בלבד.";
                $isValid = false;
            }
        }else if($target == "images"){

         $totalItemsGal = (($countedGalfiles + $countedUpfiles) - $countedDelfiles);
         if($totalItemsGal < 3){

            $convertedMsg = $this->conFilesAttr[$target]["name"] .": "
            . "חייב להכיל " 
            .$this->conFilesAttr[$target]['minMaxFiles']["min"]
            . " קבצי "
            .$this->conFilesAttr[$target]["type"] 
            . " לפחות";
            $isValid = false;
        }else if($totalItemsGal > 12){

            $convertedMsg = $this->conFilesAttr[$target]["name"] 
            . ": יכול להכיל " 
            . $this->conFilesAttr[$target]['minMaxFiles']["max"]
            ." קבצי "
            .$this->conFilesAttr[$target]["type"]  
            . " בלבד.";
            $isValid = false;
        }
    }
    ! $isValid? $msgs['status'] = false:"";
    $msgs[$target] = $isValid;
    ! $isValid? $this->customers->setMessages('errors', $target, $convertedMsg): "";
    return $msgs;
}

private function validInputs($inputes, $method){

 $myRequest = [];
 $newValRole = [];
 $massegeSuccess = [];

 foreach($inputes as $key => $value) {
            // if($key == "owner") {continue;}
    if(! isset($this->formRoles[$key])) {
        $msg = $key . " dos not exisst in our system";
        $this->customers->setMessages('errors', $key, $msg);
        return false;
    }

    $myRequest[$key] = $value;
    $newValRole[$key] = $this->formRoles[$key];
    $massegeSuccess[$key] = $this->convetedMasseges[$key] . " עודכן בהצלחה.";
    $this->customers->setMessages('success', $key, $massegeSuccess[$key]);
}

$val = ($method == "POST")? $this->validateItems($myRequest, $this->formRoles):
$this->validateItems($myRequest, $newValRole);
return $val;
}


private function validateItems($reqItems, $ruls){

 $valFiles = Validator::make($reqItems, $ruls,[
  'mimetypes' => ":attribute " . " קובץ וידאו זה לא נתמך",
  'mimes' => ":attribute " . " קובץ תמונה זה לא נתמך"
]);

 if($valFiles->fails()) {
            //$this->prityMessges($valFiles->errors()->all());
    $this->customers->setErrorsMessages($valFiles);
    return false;
}
return true;
}

public function getTarget($path, $target){
    return (strpos($path , $target) !== false)? $target: false;
}

private function prityMessges($messages){


  foreach ($messages as $key => $value) {

      $targetLoggo = $this->getTarget($value, 'loggo')? 'loggo': false;
      $targetVideo = $this->getTarget($value, 'video')? 'video':false;
      $targetGallery =  $this->getTarget($value, 'images')? 'images':false;
      $target;
      $message;

      if($targetLoggo){

        $target = $targetLoggo;
        $fNameAndMsg = explode($targetLoggo.'/', $value)[1];
        $fName = explode(' ', $fNameAndMsg)[0];
        $msg = explode($fName, $fNameAndMsg)[1];
        $message = [$targetLoggo => $fName . $msg];
    }else if($targetVideo){

        $target = $targetVideo;

        $fNameAndMsg = explode($targetVideo.'/', $value)[1];
        $fName = explode(' ', $fNameAndMsg)[0];
        $msg = explode($fName, $fNameAndMsg)[1];

        $message = [$targetVideo => $fName . $msg];
    }else if($targetGallery){

        $target = $targetGallery;
        $fNameAndMsg = explode($targetGallery.'/', $value)[1];
        $fName = explode(' ', $fNameAndMsg)[1];
        $msg = explode($fName, $fNameAndMsg)[1];
        $message = [$targetGallery => $fName . $msg];
    }else{

        $exp = explode('The ', $value);
        $targetAttr = explode(' ', $exp[1])[0];
        $target = $targetAttr;
        $message = $exp[1];
    }
    ($target)? $this->customers->setMessages('errors', $target, $message): '';
}
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
private function responseMessages($msgs){
    $messagesTosend = [];
    $messagesTosend['errors'] = isset($msgs['errors']) && count($msgs['errors'])? $this->whileMsgs($this->messages['errors'], $msgs['errors']): $this->messages['errors'];
    $messagesTosend['success'] = isset($msgs['success']) && count($msgs['success'])? $this->whileMsgs($this->messages['success'], $msgs['success']): $this->messages['success'];
    return $messagesTosend;
}

private function whileMsgs($firstMsgs, $secondMsgs){

    $msgsArr = [
        $firstMsgs,
        $secondMsgs
    ];
    $msgsReturn = [];

    if(empty($firstMsgs)) return $secondMsgs;

    while ($msgsCurrent = current($msgsArr)) {

        foreach ($msgsCurrent as $key => $value) {

            if(count($msgsCurrent[$key])){

                (isset($msgsReturn[$key]) && count($msgsReturn[$key]))? array_push($msgsReturn[$key], $msgsCurrent[$key]): $msgsReturn[$key] = $msgsCurrent[$key];
            }
        }
        next($msgsArr);
    }

    return $msgsReturn;
}
}

