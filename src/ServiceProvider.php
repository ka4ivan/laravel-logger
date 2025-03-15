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
        $this->publishConfig();
        $this->publishView();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/logger.php', 'logger');

        $existingChannels = $this->app->config->get('logging.channels', []);

        $newChannels = require __DIR__.'/../config/logger.php';
        $newChannels = $newChannels['channels'] ?? [];

        $this->app->config->set(
            'logging.channels',
            array_merge($existingChannels, $newChannels)
        );

        $this->app->bind(\Ka4ivan\LaravelLogger\Llog::class, function () {
            return new \Ka4ivan\LaravelLogger\Support\Llog;
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Llog', "Ka4ivan\\LaravelLogger\\Facades\\Llog");
    }

    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/logger.php' => config_path('logger.php'),
        ], 'logger');
    }

    protected function publishView(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-logger');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('/resources/views/vendor/laravel-logger'),
        ], 'views');
    }
}
