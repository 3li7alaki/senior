<?php

namespace App\Models;

use App\Http\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Form extends Model
{
    use HasAttachment;
    protected $guarded = ['id'];

    const RELATIONS = ['questions', 'attachment'];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'form_questions');
    }
}
