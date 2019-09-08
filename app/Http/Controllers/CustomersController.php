<?php

namespace App\Http\Controllers;

use App\Http\Responses\CustomerValidationResponse; 
use App\Repositories\Customer\CustomerRepoInterface;
use App\Repositories\traits\ValidationTrait; 
use Illuminate\Http\Request;

class CustomersController extends Controller
{

    use ValidationTrait;
    
    protected $customerRepo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepoInterface $customerRepo)
    {
        // $this->middleware('cors');
        $this->middleware('auth:api', ['except' => ['index', 'store', 'update']]);
        // $this->middleware('auth:api', ['except' => ['getLogin']]);
        // $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
        
        $this->customerRepo = $customerRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->customerRepo->all();
    }


    public function show($id)
    {
       return response()->json($this->customerRepo->findOrFail($id),200);
   }

   public function store(Request $request){

    // if(! auth('api')->check() || ! auth('admin')->check()) return response()->json(['error' => 'Unauthorized'], 401);
    $checkHaveItems = (! $request->file('files') || ! $request->file('files')->count()) || (!$request->formInputs || ! $request->formInputs->count());
    if($checkHaveItems) return response()->json(['error' => 'No Data Sented'], 200);
    $validateItems =   $this->validateResponse($request->all(), $request->method());
    $resault = $validateItems['status']? $this->customerRepo->create($validateItems): $validateItems;

    /******* get and send back messages ******/
    return response()->json(['message' => "customer Successfully created!", 'res' => $resault],200);
}

public function update(Request $request, $id){

    // if(! auth('api')->check() || ! auth('admin')->check()) return response()->json(['error' => 'Unauthorized'], 401);
    $requests = $request->only(['files', 'formInputs', 'filesToDelete']);
    if(! count($requests)) return response()->json(['error' => 'No Data Sented'], 200);
    $validateItems =   $this->validateResponse($requests, $request->method(), $id);
    // return response()->json($validateItems,200);
    $resault = $validateItems['status']? $this->customerRepo->update($validateItems, $id): $validateItems;

    /******* get and send back messages ******/
    return response()->json(['message' => "customer Successfully updated!", 'res' => $resault],200);
}

public function destroy($id){

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

}

