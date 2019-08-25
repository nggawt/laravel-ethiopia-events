<?php
namespace App\Repositories\Article;


interface ArticleRepositoryInterface
{
    /**
     * Get's a article by it's ID
     *
     * @param int
     */
    public function show($id);

    /**
     * Get's all articles.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create articles.
     *
     * @return mixed
     */
    public function create(array $article);

    /**
     * Deletes a article.
     *
     * @param int
     */
    public function delete($id);

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update(array $items, $id);
}