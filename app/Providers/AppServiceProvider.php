<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Predis\Client;
use Predis\ClientInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ClientInterface::class, fn() => new Client([
            'host'     => config('database.redis.default.host'),
            'port'     => config('database.redis.default.port'),
            'password' => config('database.redis.default.password'),
        ]));
    }
}
