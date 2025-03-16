<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Models\Traits;

use Ka4ivan\LaravelLogger\Facades\Llog;

trait HasTracking
{
    protected static function bootHasTracking()
    {
        $url = request()->url();
        $ip = request()->ip();

        static::creating(function($model) use ($url, $ip) {
            Llog::track($model, 'created', $url, $ip, auth()->user(), ['model' => $model]);
        });

        static::updating(function($model) use ($url, $ip) {
            $changes = $model->getDirty();
            $original = $model->getOriginal();

            if (!empty($changes)) {
                $modelChanges = [];

                foreach ($changes as $field => $newValue) {
                    $modelChanges[$field] = [
                        'old' => $original[$field] ?? null,
                        'new' => $newValue,
                    ];
                }

                Llog::track($model, 'updated', $url, $ip, auth()->user(), ['changes' => $modelChanges]);
            }
        });

        static::deleting(function($model) use ($url, $ip) {
            Llog::track($model, 'deleted', $url, $ip, auth()->user());
        });
    }
}
