<?php

namespace App\Http\Traits;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @method static deleting(\Closure $param)
 */
trait HasAttachment
{
    public static function bootHasAttachment(): void
    {
        static::deleting(function (Model $model) {
            $model->attachment()->delete();
        });
    }

    /**
     * Define the "attachments" relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }
}
