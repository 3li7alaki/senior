<?php

namespace App\Models;

use App\Http\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class ChildDiagnose extends Pivot
{
    use HasAttachments;
    protected $table = 'child_diagnoses';
    protected $guarded = ['id'];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id', 'id');
    }

    public function diagnose(): BelongsTo
    {
        return $this->belongsTo(Diagnose::class, 'diagnose_id', 'id');
    }

    public function resolveRouteBinding($value, $field = null): ?ChildDiagnose
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

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function getRouteKey()
    {
        return $this->id;
    }

    public function getIncrementing(): bool
    {
        return true;
    }
}
