<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diagnose extends Model
{
    protected $guarded = ['id'];

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Child::class, 'child_diagnoses')->using(ChildDiagnose::class)
            ->as('child_diagnoses');
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_diagnoses');
    }
}
