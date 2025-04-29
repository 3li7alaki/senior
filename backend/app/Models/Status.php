<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'name_ar',
        'type',
    ];

    public function childPrograms(): HasMany
    {
        return $this->hasMany(ChildProgram::class);
    }

    public function newStatusChanges(): HasMany
    {
        return $this->hasMany(StatusChange::class, 'new_status_id', 'id');
    }

    public function oldStatusChanges(): HasMany
    {
        return $this->hasMany(StatusChange::class, 'old_status_id', 'id');
    }

    public function isUsed(): bool
    {
        return $this->childPrograms()->exists() || $this->newStatusChanges()->exists() || $this->oldStatusChanges()->exists();
    }
}
