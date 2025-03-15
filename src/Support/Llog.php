<?php

namespace Ka4ivan\LaravelLogger\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Llog
{
    public function info(string $message = null, array $context = []): void
    {
        $this->writeLog('info', $message, $context);
    }

    public function warning(string $message = null, array $context = []): void
    {
        $this->writeLog('warning', $message, $context);
    }

    public function error(string $message = null, array $context = []): void
    {
        $this->writeLog('error', $message, $context);
    }

    public function track(Model $model, string $action, array $context = [])
    {
        $channel = config('logger.tracking.default');
        $modelClass = ucfirst($model->getMorphClass());

        Log::channel($channel)->info("{$modelClass} {$action} - {$model->id}", $context);
    }

    protected function writeLog(string $method, string $message = null, array $context = [])
    {
        $logMessage = $message ? "Message: {$message}" : '';

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        if (isset($backtrace[2])) {
            $caller = $backtrace[2];
            $callerInfo = "Called from {$caller['file']}: {$caller['line']}";
            $logMessage .= $logMessage ? ". {$callerInfo}" : $callerInfo;
        }

        $channel = config('logger.default');

        Log::channel($channel)->{$method}($logMessage, $context);
    }
}
