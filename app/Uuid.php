<?php
namespace App;

use Illuminate\Support\Str;
use Auth;

/**
 * UUID Trait
 */
trait Uuid
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->keyType = 'string';
            $model->{$model->getKeyName()} = Str::uuid()->toString();
            if (Auth::check()) {
                $model->created_by = Auth::user()->id;
            }
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        });
    }
}
