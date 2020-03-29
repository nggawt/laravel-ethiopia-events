<?php

namespace App\Providers;


use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\ArticleRepositoryInterface;
use App\Repositories\Customer\CustomerRepoInterface;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->singleton(CustomerRepoInterface::class, CustomerRepository::class);
    }
}
