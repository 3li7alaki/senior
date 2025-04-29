<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $guarded = ['id'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
