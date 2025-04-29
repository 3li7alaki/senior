<?php

namespace App\Models;

use App\Enums\UsersTypes;
use App\Helpers\Util;
use App\Http\Traits\HasFile;
use App\Http\Traits\HasHandler;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class Attachment extends Model implements HasMedia
{
    use HasFile, HasHandler;

    protected $guarded = ['id'];
    protected $files = [
        'path',
    ];
    const CHILDMODELS = [
        'App\Models\Child',
        'App\Models\DataFile',
        'App\Models\ChildDiagnose',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function resolveRouteBinding($value, $field = null): ?Attachment
    {
        if (User::isAdmin(Auth::user()))
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->firstOrFail();
        else
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->where('guardian_id', Auth::id())
                ->firstOrFail();
    }

    public static function boot()
    {
        parent::boot();

        static ::creating(function ($attachment) {
            if (in_array($attachment->attachable_type, Attachment::CHILDMODELS) && Auth::user()->type === UsersTypes::GUARDIAN->value) {
                if ($attachment->attachable_type === 'App\Models\Child')
                    $attachment->guardian_id = $attachment->attachable->guardian_id;
                else {
                    $attachment->guardian_id = $attachment->attachable->child->guardian_id ?? null;
                }
            }
        });

        static ::updating(function ($attachment) {
            if (in_array($attachment->attachable_type, Attachment::CHILDMODELS) && Auth::user()->type === UsersTypes::GUARDIAN->value) {
                if ($attachment->attachable_type === 'App\Models\Child')
                    $attachment->guardian_id = $attachment->attachable->guardian_id;
                else {
                    $attachment->guardian_id = $attachment->attachable->child->guardian_id ?? null;
                }
            }
        });

        static ::deleting(function ($attachment) {
            if ($attachment->getMedia('attachments')->count() > 0) {
                Util::deleteFile($attachment->getMedia('attachments')->first()->getPath());
                $attachment->getMedia('attachments')->map(function ($media) {
                    $media->delete();
                });
            }
        });
    }
}
