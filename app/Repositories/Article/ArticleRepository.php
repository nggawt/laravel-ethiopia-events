<?php

namespace App\Repositories\Article;


use App\Article;
use Illuminate\Support\Arr;

class ArticleRepository implements ArticleRepositoryInterface
{

	protected $article;

	public function __construct(Article $article)
    {
        $this->article = $article;
    }

	/**
     * Get's a article by it's ID
     *
     * @param int
     */
    public function show($id){
    	
    }

    /**
     * Get's all articles.
     *
     * @return mixed
     */
    public function all(){

    	return response()->json($this->transformArticles(), 200);
    }

    public function create(array $article){

    	$article['user_id'] = auth('api')->user()? auth('api')->user()->id: auth('admin')->user()->id;
        $article['confirmed'] = $article['confirmed']? 1: 0;
        $article['slug'] = slug_heb($article['title']);
        $this->article->create($article);

        return response()->json(["message" => "your article/post was created succesfully!", "status" => true, 'article' => $article], 200);
    }
    /**
     * Deletes a article.
     *
     * @param int
     */
    public function delete($id){

    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update(array $items, $id){

    	isset($items['title'])? $items['slug'] = slug_heb($items['title']): '';
        Arr::has($items, 'confirmed')? $items['confirmed'] = $items['confirmed']? 1: 0: '';
       
        $this->article->findOrfail($id)->update($items);
        return true;
    }

    protected function transformArticles(){

    	return $this->article->all()->map(function($article){
    		return [
    			"id" => $article->id,
    			"user_id" => $article->user_id,
    			"name" => $article->name,
    			"body" => $article->body,
    			"title" => $article->title,
    			"date" => $article->date,
    			"confirmed" => $article->confirmed,
    			"created_at" => $article->created_at
    		];
    	});
    }

}