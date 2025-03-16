<?php

namespace Ka4ivan\LaravelLogger\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Llog
{
    /**
    * Logs emergency-level messages: system is unusable.
    *
    * @param string|null $message
    * @param array $context
    */
    public function emergency(string|array $message = null, array $context = []): void
    {
        $this->writeLog('emergency', $message, $context);
    }

    /**
     * Logs alert-level messages: action must be taken immediately.
     * Example: Entire website down, database unavailable, etc.
     *
     * @param string|null $message
     * @param array $context
     */
    public function alert(string|array $message = null, array $context = []): void
    {
        $this->writeLog('alert', $message, $context);
    }

    /**
     * Logs critical-level messages: application component unavailable, unexpected exception, etc.
     *
     * @param string|null $message
     * @param array $context
     */
    public function critical(string|array $message = null, array $context = []): void
    {
        $this->writeLog('critical', $message, $context);
    }

    /**
     * Logs error-level messages: runtime errors that do not require immediate action but should be monitored.
     *
     * @param string|null $message
     * @param array $context
     */
    public function error(string|array $message = null, array $context = []): void
    {
        $this->writeLog('error', $message, $context);
    }

    /**
     * Logs warning-level messages: exceptional occurrences that are not errors but may require attention.
     *
     * @param string|null $message
     * @param array $context
     */
    public function warning(string|array $message = null, array $context = []): void
    {
        $this->writeLog('warning', $message, $context);
    }

    /**
     * Logs notice-level messages: normal but significant events.
     *
     * @param string|null $message
     * @param array $context
     */
    public function notice(string|array $message = null, array $context = []): void
    {
        $this->writeLog('notice', $message, $context);
    }

    /**
     * Logs info-level messages: interesting events like user logins, SQL logs, etc.
     *
     * @param string|null $message
     * @param array $context
     */
    public function info(string|array $message = null, array $context = []): void
    {
        $this->writeLog('info', $message, $context);
    }

    /**
     * Logs debug-level messages: detailed debug information.
     *
     * @param string|null $message
     * @param array $context
     */
    public function debug(string|array $message = null, array $context = []): void
    {
        $this->writeLog('debug', $message, $context);
    }

    /**
     * Logs messages with an arbitrary level.
     *
     * @param string $level The log level (e.g., 'info', 'error', 'debug').
     * @param string|null $message
     * @param array $context
     */
    public function log(string $level, string|array $message = null, array $context = []): void
    {
        $this->writeLog($level, $message, $context);
    }

    /**
     * Tracking model events
     *
     * @param Model $model
     * @param string $action
     * @param string|null $url
     * @param string|null $ip
     * @param Model|null $user
     * @param array $context
     */
    public function track(Model $model, string $action, string $url = null, string $ip = null, Model $user = null, array $context = [])
    {
        $channel = config('logger.tracking.default');
        $modelClass = ucfirst($model->getMorphClass());

        Log::channel($channel)->info(json_encode([
            'id' => $model->id,
            'model' => $modelClass,
            'action' => $action,
            'url' => $url,
            'ip' => $ip,
            'user' => $user,
            'data' => $context,
        ]));
    }

    protected function writeLog(string $method, string|array $message = null, array $context = [])
    {
        $channel = config('logger.default');
        $logMessage = is_string($message) ? $message : '';
        $context = is_array($message) ? array_merge($context, $message) : $context;
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        if (isset($backtrace[2])) {
            $caller = $backtrace[2];
            $callerInfo = " at {$caller['file']}: {$caller['line']}";
            $logMessage .= $callerInfo;
        }

        Log::channel($channel)->{$method}(json_encode([
            'message' => $logMessage,
            'data' => $context,
            'ip' => request()->ip(),
            'user' => auth()->user(),
        ]));
    }
}
