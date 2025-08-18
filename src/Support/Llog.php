<?php

namespace Ka4ivan\LaravelLogger\Support;

use Illuminate\Database\Eloquent\Model;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Llog extends AbstractLogger implements LoggerInterface
{
    /**
     * @var LoggerInterface $logger
     */
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs emergency-level messages: system is unusable.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function emergency($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Logs alert-level messages: action must be taken immediately.
     * Example: Entire website down, database unavailable, etc.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function alert($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::ALERT, $message, $context);
    }

    /**
     * Logs critical-level messages: application component unavailable, unexpected exception, etc.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function critical($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Logs error-level messages: runtime errors that do not require immediate action but should be monitored.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function error($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::ERROR, $message, $context);
    }

    /**
     * Logs warning-level messages: exceptional occurrences that are not errors but may require attention.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function warning($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::WARNING, $message, $context);
    }

    /**
     * Logs notice-level messages: normal but significant events.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function notice($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Logs info-level messages: interesting events like user logins, SQL logs, etc.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function info($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::INFO, $message, $context);
    }

    /**
     * Logs debug-level messages: detailed debug information.
     *
     * @param string|array|null $message
     * @param array $context
     */
    public function debug($message = null, array $context = []): void
    {
        $this->writeLog(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs messages with an arbitrary level.
     *
     * @param string $level The log level (e.g., 'info', 'error', 'debug').
     * @param string|array|null $message
     * @param array $context
     */
    public function log($level, $message, array $context = []): void
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
    public function track(
        Model $model,
        string $action,
        ?string $url = null,
        ?string $ip = null,
        ?Model $user = null,
        array $context = []
    ): void {
        $channel = config('logger.tracking.default');

        $logData = array_merge($context, [
            'meta' => [
                'url' => $url ?? request()->fullUrl(),
                'ip' => $ip ?? request()->ip(),
                'user' => $user?->only(config('logger.user.fields')),
            ]
        ]);

        $this->logger->channel($channel)->log(
            LogLevel::INFO,
            ucfirst($model->getMorphClass()) . " was {$action} with {$model->getKeyName()}: {$model->getKey()}",
            $logData
        );
    }

    /**
     * Logs a message with a given severity level.
     *
     * @param string $level The log level (e.g., 'info', 'error', 'debug').
     * @param string|array|null $message
     * @param array $context
     */
    protected function writeLog(string $level, string|array $message = null, array $context = []): void
    {
        $channel = config('logger.default');
        $messageArray = is_array($message) ? $message : json_decode($message ?? '', true);
        $logMessage = is_array($messageArray) ? '' : $message;
        $context = is_array($messageArray) ? array_merge($context, $messageArray) : $context;

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        $caller = $backtrace[2] ?? null;
        $callerMethod = $backtrace[3] ?? null;
        $callerText = $caller ? "{$caller['file']}:{$callerMethod['function']} line {$caller['line']}" : 'unknown';

        $logMessage = ($logMessage && is_string($logMessage)) ? $logMessage . " at {$callerText}" : $callerText;

        $this->logger->channel($channel)->log(
            $level,
            $logMessage ?: $callerText,
            $context
        );
    }
}
