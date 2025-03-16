<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Models\Traits;

use Ka4ivan\LaravelLogger\Facades\Llog;

trait HasTracking
{
    protected static function bootHasTracking()
    {
        $url = request()->url();

        static::creating(function ($model) use ($url) {
            Llog::track($model, 'created', $url, auth()->user(), ['model' => $model]);
        });

        static::updating(function ($model) use ($url) {
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

                Llog::track($model, 'updated', $url, auth()->user(), ['changes' => $modelChanges]);
            }
        });

        static::deleting(function ($model) use ($url) {
            Llog::track($model, 'deleted', $url, auth()->user());
        });
    }
}
