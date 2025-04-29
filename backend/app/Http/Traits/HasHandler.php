<?php

namespace App\Http\Traits;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

/**
 * @method static updating(\Closure $param)
 * @method static creating(\Closure $param)
 */
trait HasHandler
{
    public static function bootHasHandler(): void
    {
        static::creating(function (Model $model) {
            $user = Auth::user();
            if ($user) {
                $model->last_handler = $user->name;
            }
        });
        static::updating(function (Model $model) {
            $user = Auth::user();
            if ($user) {
                $model->last_handler = $user->name;
            }
        });
    }
}
