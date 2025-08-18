<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class LogViewerController
{
    /**
     * Compatibility with 1.x versions
     */
    public function index()
    {
        return Redirect::to('log-viewer');
    }
}
