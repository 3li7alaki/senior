<?php

namespace App\Models;

use App\Http\Traits\HasFile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class DataFile extends Model implements HasMedia
{
    use HasFile;

    protected $guarded = ['id'];
    protected $files = [
        'father_national_id',
        'mother_national_id',
    ];
    protected $casts = [
        'heart_check_date' => 'datetime',
        'thyroid_check_date' => 'datetime',
        'sight_check_date' => 'datetime',
        'hearing_check_date' => 'datetime',
    ];


    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function resolveRouteBinding($value, $field = null): ?DataFile
    {
        if (User::isAdmin(Auth::user()))
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->firstOrFail();
        else
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->whereHas('child', function ($query) {
                    $query->where('guardian_id', Auth::id());
                })
                ->firstOrFail();
    }
}
