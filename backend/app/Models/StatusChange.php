<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StatusChange extends Model
{
    protected $guarded = ['id'];

    const RELATIONS = ['oldStatus', 'newStatus', 'user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->select(['name', 'id']);
    }

    public function childProgram(): belongsTo
    {
        return $this->belongsTo(ChildProgram::class, 'child_program_id', 'id');
    }

    public function oldStatus(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'old_status_id');
    }

    public function newStatus(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'new_status_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }
}
