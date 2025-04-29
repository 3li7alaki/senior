<?php

namespace App\Http\Controllers\Program;

use App\Enums\Statuses;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Program\AdminChildProgramRequest;
use App\Mail\Accepted;
use App\Mail\StatusChanged;
use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\Status;
use App\Models\StatusChange;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use function Symfony\Component\String\s;

class AdminChildProgramController extends Controller
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

    public function store(AdminChildProgramRequest $request, Child $child): JsonResponse
    {
        $input = $request->validated();
//        if ($child->programs()->where('program_id', $input['program_id'])->wherePivot('status_id', Util::getStatusId(Statuses::REJECTED))->exists()) {
//            return Response::failure('common.child_already_enrolled');
//        }
        $childProgram = ChildProgram::query()->create([
            'child_id' => $child->id,
            'program_id' => $input['program_id'],
            'status_id' => $input['status_id'] ?? Util::getStatusId(Statuses::NEW),
            'schedule' => $input['schedule'] ?? [],
            'created_at' => $input['status_id'] == Util::getStatusId(Statuses::NEW) ? $input['date'] : Carbon::now()->format('Y-m-d'),
        ]);
        if (!$childProgram) {
            return Response::failure('common.error');
        }
        $childProgram->statusChanges()->create([
            'child_program_id' => $childProgram->id,
            'new_status_id' => $childProgram->status_id,
            'user_id' => Auth::id(),
            'note' => $input['note'] ?? '',
            'date' => $input['date'] ?? Carbon::now()->format('Y-m-d'),
        ]);
        return Response::success($childProgram->load(ChildProgram::RELATIONS));
    }

    public function update(AdminChildProgramRequest $request, Child $child, ChildProgram $childProgram): JsonResponse
    {
        $input = $request->validated();
        if ($childProgram->status_id != $input['status_id']) {
            $statusChange = $childProgram->statusChanges()->create([
                'child_program_id' => $childProgram->id,
                'old_status_id' => $childProgram->status_id,
                'new_status_id' => $input['status_id'],
                'user_id' => Auth::id(),
                'note' => $input['note'] ?? '',
                'date' => $input['date'] ?? Carbon::now()->format('Y-m-d'),
            ]);
            $this->notificationService->statusChanged($childProgram, $statusChange)
                ->notifyAdmins()
                ->emailAdmins(new StatusChanged($childProgram, $statusChange));
            if ($input['status_id'] == Util::getStatusId(Statuses::ACCEPTED) && $child->guardian) {
                $this->notificationService->accept($childProgram)
                    ->to($child->guardian)
                    ->notify()
                    ->email(new Accepted($childProgram));
            } elseif ($input['status_id'] == Util::getStatusId(Statuses::REJECTED)) {
                $input = array_merge($input, [
                    'rejected' => true,
                    'rejection_reason' => $input['note'] ?? '',
                    'rejected_at' => Carbon::now()->format('Y-m-d'),
                ]);
            }
        }
        $childProgram->update($input);
        return Response::success($childProgram->load(ChildProgram::RELATIONS));
    }

    public function show(Child $child, ChildProgram $childProgram): JsonResponse
    {
        return Response::success($childProgram->load(ChildProgram::RELATIONS));
    }

    public function destroy(Child $child, ChildProgram $childProgram): JsonResponse
    {
        return Response::success($childProgram->delete());
    }
}
