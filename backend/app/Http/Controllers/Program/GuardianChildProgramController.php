<?php

namespace App\Http\Controllers\Program;

use App\Enums\Statuses;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Mail\NewApplication;
use App\Mail\ReceivedApplication;
use App\Mail\Renewed;
use App\Mail\StatusChanged;
use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\Status;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GuardianChildProgramController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Child $child): JsonResponse
    {
        $programs = $child->programs;
        foreach ($programs as $program) {
            $program['status'] = Status::query()->find($program->child_program->status_id);
        }
        return Response::success($programs);
    }

    public function applicablePrograms(Child $child): JsonResponse
    {
        return Response::success($child->applicablePrograms());
    }

    public function store(Child $child, Program $program): JsonResponse
    {
        if (!$child->qualifiesForProgram($program)) {
            return Response::failure('program.not_qualify');
        }
        $childProgram = ChildProgram::query()->create([
            'child_id' => $child->id,
            'program_id' => $program->id,
            'status_id' => Util::getStatusId(Statuses::NEW),
        ]);
        if ($childProgram->child->guardian) {
            $this->notificationService->newApplication($childProgram)
            ->to($child->guardian)
            ->notify()
            ->email(new NewApplication($childProgram));
        }
        $this->notificationService->newApplication($childProgram, true)
            ->notifyAdmins()
            ->emailAdmins(new ReceivedApplication($childProgram));
        $childProgram->statusChanges()->create([
            'child_program_id' => $childProgram->id,
            'new_status_id' => $childProgram->status_id,
            'user_id' => Auth::id(),
            'note' => $input['note'] ?? '',
            'date' => $input['date'] ?? Carbon::now()->format('Y-m-d'),
        ]);
        return Response::success($child->programs()->get());
    }

    public function update(Child $child, ChildProgram $childProgram): JsonResponse
    {
        $childProgram->update([
            'status_id' => Util::getStatusId(Statuses::WAITING),
            'expired_at' => null,
            'rejected' => false,
            'rejected_reason' => null,
        ]);
        $statusChange = $childProgram->statusChanges()->create([
            'child_program_id' => $childProgram->id,
            'old_status_id' => Util::getStatusId(Statuses::EXPIRED),
            'new_status_id' => Util::getStatusId(Statuses::WAITING),
            'user_id' => Auth::id(),
            'note' => 'تم تجدبد الطلب',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
        $this->notificationService->statusChanged($childProgram, $statusChange)
            ->notifyAdmins()
            ->emailAdmins(new StatusChanged($childProgram, $statusChange));
        $this->notificationService->renewed($childProgram)
            ->notifyAdmins()
            ->emailAdmins(new Renewed($childProgram));
        return Response::success($childProgram->load(ChildProgram::RELATIONS));
    }

    public function show(Child $child, ChildProgram $childProgram): JsonResponse
    {
        return Response::success($childProgram->load(ChildProgram::RELATIONS));
    }
}
