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
        "confirmed" => "boolean",
        "date" => "required|date",
    ];

       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
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

        $items = collect($request->all())->except('_method')->intersectByKeys($this->itemsRule)->toArray();
        $validty =\Validator::make($items, $this->itemsRule);

        if($validty->fails()) return response()->json([$request->all(), "message" => "you have an errors!", 'errors' => $validty->errors()->all(), "status" => false], 200);

        $items['user_id'] = auth('api')->user()? auth('api')->user()->id: auth('admin')->user()->id;
        $items['confirmed'] = 0;
        $items['slug'] = slug_heb($items['title']);
        Post::create($items);
        return response()->json(["message" => "your article/post was created succesfully!", "status" => true, 'validItems' => $validItems], 200);
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
        // if(! Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        $valItems = $this->validate($request, $this->itemsRule);
        $valItems['title']? $valItems['slug'] = slug_heb($valItems['title']): '';
        $request->has('confirmed')? $valItems['confirmed'] = $valItems['confirmed']? 1: 0: '';
       
        $blog->update($valItems);
        return response()->json(["message" => "your article/post was updated succesfully!", "status" => true, 'validItems' => $valItems], 200);
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
