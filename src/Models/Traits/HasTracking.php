<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Models\Traits;

use Ka4ivan\LaravelLogger\Facades\Llog;

trait HasTracking
{
    protected static function bootHasTracking()
    {
        $user = auth()->user();
        $url = request()->url();

        static::creating(function ($model) use ($user, $url) {
            Llog::track($model, 'created', $url, $user, ['model' => $model]);
        });

        static::updating(function ($model) use ($user, $url) {
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

                Llog::track($model, 'updated', $url, $user, ['changes' => $modelChanges]);
            }
        });

        static::deleting(function ($model) use ($user, $url) {
            Llog::track($model, 'deleted', $url, $user);
        });
    }
}
