<?php

namespace App\Providers;

use App\Services\AuthorizationServer;
use Illuminate\Support\ServiceProvider;

class AuthorizationServerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthorizationServer::class, function ($app) {
            return new AuthorizationServer();
        });
    }
}
