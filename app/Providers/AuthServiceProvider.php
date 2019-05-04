<?php

namespace App\Providers;

use App\Policies\PostPolicy;
use App\Post;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Post' => PostPolicy::class,
    ];
    // protected $policies = [
    //     Post::class => PostPolicy::class,
    // ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Gate::define('view', 'App\Policies\PostPolicy@view');

        // Gate::before(function ($user, $ability) {
                // return true;

            // if ($user->isSuperAdmin()) {
            //     return true;
            // }else{
            //     return false;
            // }
        // });

         Gate::define('posts', function ($post) {
            return true;
        });
    }
}
