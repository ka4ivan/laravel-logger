<?php

namespace Ka4ivan\LaravelLogger\Facades;

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
