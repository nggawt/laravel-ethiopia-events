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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->singleton(CustomerRepoInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Str::macro('slug_heb', function($str = ""){
            $trimmed = trim($str);
            $exploded = explode(" ", $trimmed);
            $slug;
            foreach($exploded as $item):
                $slug = isset($slug) ? $slug ."-". $item: $item;
            endforeach;

            return isset($slug)? $slug: $str;
        });
        // Schema::defaultStringLength(191);
    }
}
