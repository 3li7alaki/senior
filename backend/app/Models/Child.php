<?php

namespace App\Models;

use App\Enums\Statuses;
use App\Helpers\Util;
use App\Http\Traits\HasAttachments;
use App\Http\Traits\HasFile;
use App\Http\Traits\HasHandler;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class Child extends Model implements HasMedia
{
    use HasFile, HasAttachments, HasHandler;
    protected $files = [
        'photo',
        'national_id',
    ];
    protected $guarded = ['id'];

    const RELATIONS = ['diagnoses', 'nationality', 'guardian', 'dataFile', 'attachments', 'programs'];

    protected $casts = [
        'birth_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['age'];

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id', 'id');
    }

    public function diagnoses(): BelongsToMany
    {
        $pivot_fields = ['id','date', 'institution', 'symptoms_age', 'symptoms', 'child_id', 'diagnose_id'];
        return $this->belongsToMany(Diagnose::class, 'child_diagnoses')
            ->using(ChildDiagnose::class)
            ->as('diagnose')
            ->withPivot($pivot_fields);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class, 'nationality_id', 'id');
    }

    public function dataFile(): HasOne
    {
        return $this->hasOne(DataFile::class);
    }

    public function programs(): BelongsToMany
    {
        $pivot_fields = ['id', 'child_id', 'program_id', 'status_id', 'schedule','evaluation_schedule', 'active', 'rejected', 'rejection_reason'];
        return $this->belongsToMany(Program::class, 'child_programs')
            ->using(ChildProgram::class)
            ->as('child_program')
            ->orderByPivot('rejected', 'desc')
            ->withPivot($pivot_fields);
    }

    public function qualifiesForProgram(Program $program): bool
    {
        if ($this->applicablePrograms()->where('can_apply', true)->where('id', $program->id)->isNotEmpty()) {
            return true;
        }
        return false;
    }

    public function applicablePrograms(): Collection
    {
        $childPrograms = $this->programs()
            ->wherePivot('status_id', '!=', Util::getStatusId(Statuses::WITHDRAWAL))
            ->get()->pluck('id');
        $programs = Program::query()->whereNotIn('id', $childPrograms)->with('diagnoses')->get();
        $checkedPrograms = [];
        foreach ($programs as $program) {
            $checkedProgram = [];
            $checkedProgram['can_apply'] = true;
            if ($program->gender != 'all' && $program->gender != $this->gender) {
                $checkedProgram['fails'][] = __('program.gender_fails', ['program_gender' => $program->gender, 'child_gender' => $this->gender]);
                $checkedProgram['can_apply'] = false;
            } else {
                $checkedProgram['passes'][] = __('program.gender_pass');
            }
            $age = \Illuminate\Support\Carbon::parse($this->birth_date)->diffInYears();
            $maxAge = $this->gender == 'male' ? $program->max_age_male : $program->max_age_female;
            if (!$program->all_ages && ($age < $program->min_age || $age > $maxAge)) {
                $checkedProgram['fails'][] = __('program.age_fails', ['min' => $program->min_age, 'max' => $maxAge, 'age' => $age]);
                $checkedProgram['can_apply'] = false;
            } else {
                $checkedProgram['passes'][] = __('program.age_pass');
            }
            if (!$program->all_diagnoses) {
                $diagnoses = $program->diagnoses()->get();
                foreach ($diagnoses as $diagnose) {
                    if (!$this->diagnoses()->where('diagnose_id', $diagnose->id)->exists()) {
                        $checkedProgram['fails'][] = __('program.diagnose_fails', ['diagnose' => $diagnose->name]);
                        $checkedProgram['can_apply'] = false;
                    } else {
                        $checkedProgram['passes'][] = __('program.diagnose_pass', ['diagnose' => $diagnose->name]);
                    }
                }
            }
            $checkedPrograms[] = array_merge($program->toArray(), $checkedProgram);
        }
        return collect($checkedPrograms);
    }

    public function resolveRouteBinding($value, $field = null): ?Child
    {
        if (User::isAdmin(Auth::user()))
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->firstOrFail();
        else
            return $this
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->where('guardian_id', Auth::id())
                ->firstOrFail();
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        // Format with timezone offset (+03:00 for Bahrain)
        return $date->format('Y-m-d\TH:i:s.uP');
    }
}
