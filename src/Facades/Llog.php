<?php

namespace Ka4ivan\LaravelLogger\Facades;

/**
 * @method void info(string|null $message = null, array $context = [])
 * @method void warning(string|null $message = null, array $context = [])
 * @method void error(string|null $message = null, array $context = [])
 * @method void track(\Illuminate\Database\Eloquent\Model $model, string $action, string|null $url = null, \Illuminate\Database\Eloquent\Model|null $user = null, array $context = [])
 *
 * @see \Ka4ivan\LaravelLogger\Support\Llog
 */
class Llog extends \Illuminate\Support\Facades\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Ka4ivan\LaravelLogger\Support\Llog::class;
    }
}
