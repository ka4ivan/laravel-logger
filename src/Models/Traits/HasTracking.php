<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Models\Traits;

use Ka4ivan\LaravelLogger\Facades\Llog;

trait HasTracking
{
    protected static function bootHasTracking()
    {
        $user = auth()->user()->only(config('logger.user.fields'));

        static::creating(function ($model) use ($user) {
            Llog::track($model, 'created', [$model, 'auth' => $user]);
        });

        static::updating(function ($model) use ($user) {
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

                Llog::track($model, 'updated', ['changes' => $modelChanges, 'auth' => $user]);
            }
        });

        static::deleting(function ($model) use ($user) {
            Llog::track($model, 'deleted', ['auth' => $user]);
        });
    }
}
