<?php

namespace App\Http\Controllers;

use App\Article;
use App\Repo\traits\Messages;
use App\Repositories\Article\ArticleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
	use Messages;

    private $itemsRule = [
        "name" => "required|string|min:3",//|email|max:7",
        "title" => "required|string|min:3|max:50",
        "body" => "required|string|min:12",
        "confirmed" => "boolean",
        "date" => "required|date",
    ];

    protected $article;
       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ArticleRepositoryInterface $article)
    {
        $this->article = $article;
        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
    }

    public function index() {
    	// $this->authorize('posts', $article);
    	// $this->authorize('view', $article);
    	// if(Gate::can('view', 2)){
		return $this->article->all();
	}

	public function store(Request $request){

        $items = collect($request->all())->except('_method')->intersectByKeys($this->itemsRule);
        if($items->count() != count($this->itemsRule)) return response()->json([ "message" => "you have an errors!", 
                                                        'errors' => ["unknown data sent"], "status" => false], 422);
        $validty = Validator::make($items->toArray(), $this->itemsRule);

        if($validty->fails()) return response()->json([ "message" => "you have an errors!", 
                                                        'errors' => $validty->errors()->all(), "status" => false], 422);
        return $this->article->create($items->toArray());
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $items = collect($request->all())->except('_method')->intersectByKeys($this->itemsRule);
        if($items->count() < 1) return response()->json([ "message" => "you have an errors!", 
                                                        'errors' => ["you have no data sent"], "status" => false], 422);
        $roles = collect($this->itemsRule)->intersectByKeys($request->all())->toArray();

        $valItems = Validator::make($items->toArray(), $roles);
        if($valItems->fails()) return response()->json([ "message" => "you have an errors!", 
                                                        'errors' => $valItems->errors()->all(), "status" => false], 422);

        $this->article->update($items->toArray(), $id);
        return response()->json(["message" => "your article/post was updated succesfully!", "status" => true, 'items' => $items], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Article $article)
    {
        return ["request" => $request->all(), "article" => $article];
    }
}
