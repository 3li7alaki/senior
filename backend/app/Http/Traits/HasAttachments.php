<?php

namespace App\Http\Traits;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static deleting(\Closure $param)
 */
trait HasAttachments
{
    public static function bootHasAttachments(): void
    {
        static::deleting(function (Model $model) {
            $model->attachments()->get()->map(function ($attachment) {
                $attachment->delete();
            });
        });
    }

    /**
     * Define the "attachments" relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
