<?php

namespace App\Models;

use App\Http\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evaluation extends Model
{
    use HasAttachment;
    protected $guarded = ['id'];
    protected $casts = [
        'done' => 'boolean',
        'pass' => 'boolean',
        'date_1' => 'datetime',
        'date_2' => 'datetime',
        'date_3' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const RELATIONS = ['questions', 'childProgram', 'attachment', 'form', 'users'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'evaluation_questions')
            ->as('answer')
            ->withPivot(['id', 'answer', 'note']);
    }

    public function childProgram(): BelongsTo
    {
        return $this->belongsTo(ChildProgram::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'evaluation_users');
    }
}
