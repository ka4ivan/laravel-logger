<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Http\Controllers;

class LogViewerController
{
    public function index()
    {
        return redirect()->to('log-viewer');
    }
}
