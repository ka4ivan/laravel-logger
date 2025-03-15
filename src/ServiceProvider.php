<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(\Ka4ivan\LaravelLogger\Llog::class, function () {
            return new \Ka4ivan\LaravelLogger\Support\Llog;
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Llog', "Ka4ivan\\LaravelLogger\\Facades\\Llog");
    }
}
