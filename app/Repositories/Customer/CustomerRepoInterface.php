<?php
namespace App\Repositories\Customer;


interface CustomerRepoInterface
{
    /**
     * Get's a customer by it's ID
     *
     * @param int
     */
    public function show($id);

    /**
     * Get's all customers.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create customer.
     *
     * @return mixed
     */
    public function create(array $customer);

    /**
     * Deletes a customer.
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