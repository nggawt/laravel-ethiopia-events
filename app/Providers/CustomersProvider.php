<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repo\CustumersRepo;

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
        // $this->app->bind(CustumersRepo::class, function($app){
        //     return new CustumersRepo();
        // });
    }
}
