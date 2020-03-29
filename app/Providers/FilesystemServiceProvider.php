<?php

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
// use League\Flysystem\Dropbox\DropboxAdapter;
// use Dropbox\Client as DropboxClient;

use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Storage::extend('custom', function($app, $config)
        // {
        //     $client = new DropboxClient($config['accessToken'], $config['clientIdentifier']);

        //     return new Filesystem(new DropboxAdapter($client));
        // });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

/*

Storage::extend('dropbox', function($app, $config)
{
    $client = new DropboxClient($config['accessToken'], $config['clientIdentifier']);

    return new Filesystem(new DropboxAdapter($client));
});

*/