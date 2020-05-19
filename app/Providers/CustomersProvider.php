<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repo\CustomersRepo;

class CustomersProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(CustomersRepo::class, function($app){
        //     return new CustomersRepo();
        // });
    }
}
