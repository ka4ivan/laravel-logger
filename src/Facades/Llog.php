<?php

namespace Ka4ivan\LaravelLogger\Facades;

/**
 * @method void emergency(string|array|null $message = null, array $context = [])
 * @method void alert(string|array|null $message = null, array $context = [])
 * @method void critical(string|array|null $message = null, array $context = [])
 * @method void error(string|array|null $message = null, array $context = [])
 * @method void warning(string|array|null $message = null, array $context = [])
 * @method void notice(string|array|null $message = null, array $context = [])
 * @method void info(string|array|null $message = null, array $context = [])
 * @method void debug(string|array|null $message = null, array $context = [])
 * @method void log(string $level, string|array|null $message = null, array $context = [])
 * @method void track(\Illuminate\Database\Eloquent\Model $model, string $action, string|null $url = null, string|null $ip = null, \Illuminate\Database\Eloquent\Model|null $user = null, array $context = [])
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
