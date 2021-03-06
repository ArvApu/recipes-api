<?php

namespace App\Providers;

use App\Auth\Guards\OauthGuard;
use App\Auth\UserProviders\OauthUserProvider;
use App\Services\AuthorizationServer;
use Illuminate\Cache\Repository;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Parser;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->provider('oauth_users', function ($app, $config) {
            return new OauthUserProvider(
                $app->make(AuthorizationServer::class),
                $this->app['db']->connection($config['connection'] ?? null),
                $config['table'],
            );
        });

        $this->app['auth']->extend('oauth', function ($app, $name, $config) {
            return new OAuthGuard(
                $app['auth']->createUserProvider($config['provider']),
                $app->make('request'),
                $app->make('cache.store'),
                $app->make(Parser::class)
            );
        });
    }
}
