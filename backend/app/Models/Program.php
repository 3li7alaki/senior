<?php

namespace App\Models;

use App\Http\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Program extends Model
{
    use HasAttachments;
    protected $guarded = ['id'];
    protected $casts = [
        'days' => 'array',
    ];
    const RELATIONS = [
        'diagnoses',
        'children',
        'attachments',
    ];

    public function diagnoses(): BelongsToMany
    {
        return $this->belongsToMany(Diagnose::class, 'program_diagnoses');
    }

    public function children(): BelongsToMany
    {
        $pivot_fields = ['id', 'child_id', 'program_id', 'status_id', 'schedule','evaluation_schedule', 'active', 'rejected', 'rejection_reason', 'created_at', 'updated_at', 'rejected_at', 'waiting_at', 'expired_at'];
        return $this->belongsToMany(Child::class, 'child_programs')
            ->using(ChildProgram::class)
            ->as('child_programs')
            ->with('guardian')
            ->orderByPivot('updated_at', 'desc')
            ->withPivot($pivot_fields);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }
}
