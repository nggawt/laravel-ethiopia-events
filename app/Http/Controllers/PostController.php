<?php

namespace App\Http\Controllers;

use App\Post;
use App\Repo\traits\Messages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
	use Messages;

    private $itemsRule = [
        "name" => "required|string|min:3",//|email|max:7",
        "title" => "required|string|min:3|max:50",
        "body" => "required|string|min:12",
    ];

       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['store','update', 'destroy']]);
    }

    public function index(Post $post) {
    	// $this->authorize('posts', $post);
    	// $this->authorize('view', $post);
    	// if(Gate::can('view', 2)){
			return $post->all();//$this->getConfirmedPosts(Post::all());
    	// }

	}

	 protected function getConfirmedPosts($allPosts){
    	$posts = [];
    	foreach ($allPosts as $value) {
    		# code...
    		if($value->confirmed) $posts[] = $value;
    	}
    	return $posts;
    }

	public function store(Request $request){

		if(! (Auth::check())) return response()->json(['error' => 'Unauthorized'], 401);
        
        $req = collect($request->all())->except('_method')->toArray();
        if(count($req) < 1) return response()->json(["errors" => ["bad_request" => ['message' => "bad resquest",'type' => "errors"]]]);
        
        $val = $this->valInputs($req);

        if(! $val) return response()->json($this->getMessages(), 200);
        $req['user_id'] = auth()->user()->id;
        $req['confirmed'] = 0;
        $req['date'] = Carbon::now();
        Post::create($req);
        return response()->json($this->getMessages(), 200);
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\galary  $galary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $blog)
    {
        if(! Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        
        $requestAll = collect($request->all())->except('_method');
        if(count($requestAll) < 1) return response()->json(["errors" => ["bad_request" => ['message' => "bad resquest",'type' => "errors"]]]);
        // $isBadRequest = $this->badRequest(collect($requestAll)->except('_method'));
        // if($isBadRequest) return response()->json($this->getMessages(), 200);

        $rules = collect($this->itemsRule)->intersectByKeys($requestAll)->toArray();
        $items = $requestAll->intersectByKeys($this->itemsRule)->toArray();
        $isValid = $this->valInputs($items, $rules);

        //return ['requestAll' => $requestAll, 'rules' => $rules, 'items' => $items];

        if(! $isValid) return response()->json($this->getMessages(), 200);
        // $scheduleEvent->find($id)->update($items);
        $blog->update($items);
        return response()->json($this->getMessages(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\galary  $galary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $blog)
    {
        return ["request" => $request->all(), "blog" => $blog];
    }

    protected function valInputs(array $inputs = [], array $rules = []){

        $isBadRequest = $this->badRequest($inputs);
        if($isBadRequest) return false;
        return $this->isValid($inputs, $rules);
    }

    private function isValid(array $inputs = [], array $rules = []){
        $rules = count($rules)? $rules: $this->itemsRule;
        $validator = \Validator::make($inputs, $rules);
        $isFailed = $validator->fails();

        $isFailed? $this->setErrorsMessages($validator): $this->setSuccessMessages($inputs);
        return $isFailed? false:true;
    }

    private function badRequest($inp){
        $keys = array_keys($this->itemsRule);
        $iputCollect = collect($inp)->except($keys);

        if($iputCollect->count()){
            $msg = "Blocked User";

            $msg = [key($iputCollect->toArray()) => $msg];
            $this->setMessages('errors', key($iputCollect->toArray()), $msg);
            return true;
        }
        return false;
    }
}
