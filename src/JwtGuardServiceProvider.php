<?php

namespace JwtGuard;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class JwtGuardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['auth']->extend('jwt.guard', function ($app, $name, array $config) {
            return new JwtGuard(
                $app['auth']->createUserProvider($config['provider']),
                (new JwtToken())->buildFromRequest($app->request)
            );
        });
    }
}
