<?php

declare(strict_types=1);

namespace Ka4ivan\LaravelLogger\Models\Traits;

use Ka4ivan\LaravelLogger\Facades\Llog;

trait HasTracking
{
    /*
     * Attributes whose changes will not be logged if only they are modified.
     * However, if at least one field outside this list is changed,
     * all modifications, including these fields, will be logged.
     *
     * For example, if only `activity_at` changes in the `user` model,
     * this change will not be recorded in the logs.
     * But if `activity_at` and another field change, both will be logged.
     *
     * @var array
     */
//    protected array $untrackedAttributes = ['activity_at'];

    protected static function bootHasTracking()
    {
        $url = request()->url();
        $ip = request()->ip();
        $user = auth()->user() ?: request()->user();

        static::creating(function($model) use ($url, $ip, $user) {
            Llog::track($model, 'created', $url, $ip, $user, ['model' => $model]);
        });

        static::updating(function ($model) use ($url, $ip, $user) {
            $changes = $model->getDirty();

            if (!empty($model->untrackedAttributes) && empty(array_diff(array_keys($changes), $model->untrackedAttributes))) {
                return;
            }

            $modelChanges = collect($changes)->mapWithKeys(fn($newValue, $field) => [
                $field => ['old' => $model->getOriginal($field), 'new' => $newValue]
            ])->all();

            if ($modelChanges) {
                Llog::track($model, 'updated', $url, $ip, $user, ['changes' => $modelChanges]);
            }
        });

        static::deleting(function($model) use ($url, $ip, $user) {
            Llog::track($model, 'deleted', $url, $ip, $user);
        });
    }
}
