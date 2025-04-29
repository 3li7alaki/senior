<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Nationality extends Model
{
    protected $guarded = ['id'];

    public function children(): HasMany
    {
        return $this->hasMany(Child::class, 'nationality_id', 'id');
    }
}
