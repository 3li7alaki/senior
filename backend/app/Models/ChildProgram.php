<?php

namespace App\Models;

use App\Enums\Statuses;
use App\Helpers\Util;
use App\Http\Traits\HasAttachments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class ChildProgram extends Pivot
{
    use HasAttachments;
    protected $table = 'child_programs';
    protected $guarded = ['id'];
    protected $casts = [
        'schedule' => 'array',
        'evaluation_schedule' => 'array',
        'waiting_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $with = ['status'];
    protected $appends = ['age'];

    const RELATIONS = ['child', 'program', 'status', 'statusChanges', 'attachments', 'evaluation'];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id', 'id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class)
            ->with('attachments');
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(StatusChange::class, 'child_program_id', 'id')
            ->with(StatusChange::RELATIONS)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class, 'child_program_id', 'id');
    }

    public function reject(string $reason)
    {
        $statusChange = $this->statusChanges()->create([
            'old_status_id' => $this->status_id,
            'new_status_id' => Util::getStatusId(Statuses::REJECTED),
            'user_id' => Auth::id(),
            'note' => $reason,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->update([
            'status_id' => Util::getStatusId(Statuses::REJECTED),
            'active' => false,
            'rejected' => true,
            'rejection_reason' => $reason,
            'rejected_at' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->refresh();

        return $statusChange;
    }

    public function expire(): void
    {
        $this->statusChanges()->create([
            'old_status_id' => $this->status_id,
            'new_status_id' => Util::getStatusId(Statuses::EXPIRED),
            'user_id' => Auth::id(),
            'note' => 'مرت سنتان على تقديم الطلب',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->update([
            'status_id' => Util::getStatusId(Statuses::EXPIRED),
            'active' => false,
            'expired_at' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->refresh();
    }

    public function wait()
    {
        $statusChange = $this->statusChanges()->create([
            'old_status_id' => $this->status_id,
            'new_status_id' => Util::getStatusId(Statuses::WAITING),
            'user_id' => Auth::id(),
            'note' => 'تم اجتياز التقييم',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->update([
            'status_id' => Util::getStatusId(Statuses::WAITING),
            'waiting_at' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->child()->update(['data_file_needed' => true]);
        $this->refresh();

        return $statusChange;
    }

    public function resolveRouteBinding($value, $field = null): ?ChildProgram
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

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->created_at)->diffInYears(Carbon::parse($this->child->birth_date));
    }
}
