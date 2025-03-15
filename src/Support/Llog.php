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

    public function track(Model $model, string $action, string $url = null, Model $user = null, array $context = [])
    {
        $channel = config('logger.tracking.default');
        $modelClass = ucfirst($model->getMorphClass());

        Log::channel($channel)->info(json_encode([
            'id' => $model->id,
            'model' => $modelClass,
            'action' => $action,
            'url' => $url,
            'user' => $user,
            'data' => $context,
        ]));
    }

    protected function writeLog(string $method, string $message = null, array $context = [])
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        if (isset($backtrace[2])) {
            $caller = $backtrace[2];
            $callerInfo = " at {$caller['file']}: {$caller['line']}";
            $message .= $callerInfo;
        }

        $channel = config('logger.default');

        Log::channel($channel)->{$method}(json_encode([
            'message' => $message,
            'data' => $context,
            'user' => auth()->user(),
        ]));
    }
}
