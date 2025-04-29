<?php

namespace App\Http\Controllers\Evaluation;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\EvaluationRequest;
use App\Mail\EvaluationScheduled;
use App\Mail\Rejected;
use App\Mail\StatusChanged;
use App\Mail\WaitingList;
use App\Models\ChildProgram;
use App\Models\Evaluation;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class EvaluationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(EvaluationRequest $request, ChildProgram $childProgram): JsonResponse
    {
        $input = $request->validated();
        if ($childProgram->evaluation) {
            return Response::failure('common.evaluation_exists');
        }
        $evaluation = $childProgram->evaluation()->create($input);
        $evaluation->questions()->attach($input['questions']);
        $evaluation->users()->attach($input['users'] ?? [Auth::id()]);

        if ($input['done']) {
            if ($input['pass']) {
                $statusChange = $childProgram->wait();
                if ($childProgram->child->guardian) {
                    $this->notificationService->wait($childProgram)
                        ->to($childProgram->child->guardian)
                        ->notify()
                        ->email(new WaitingList($childProgram));
                }
            } else {
                $statusChange = $childProgram->reject('لم يتم اجتياز التقييم');
                if ($childProgram->child->guardian) {
                    $this->notificationService->reject($childProgram, $childProgram->rejection_reason, $input['note'])
                        ->to($childProgram->child->guardian)
                        ->notify()
                        ->email(new Rejected($childProgram));
                }
            }
            $this->notificationService->statusChanged($childProgram, $statusChange)
                ->notifyAdmins()
                ->emailAdmins(new StatusChanged($childProgram, $statusChange));
        }
        if (isset($input['path'])) {
            $evaluation->attachment()->create([
                'path' => $input['path'],
                'name' => 'مرفق التقييم'
            ]);
        }
        return Response::success($evaluation->load(Evaluation::RELATIONS));
    }

    public function update(EvaluationRequest $request, ChildProgram $childProgram): JsonResponse
    {
        $evaluation = $childProgram->evaluation;
        $input = $request->validated();
        if ($input['done'] && !$evaluation->done) {
            if ($input['pass']) {
                $statusChange = $childProgram->wait();
                if ($childProgram->child->guardian) {
                    $this->notificationService->wait($childProgram)
                        ->to($childProgram->child->guardian)
                        ->notify()
                        ->email(new WaitingList($childProgram));
                }
            } else {
                $statusChange = $childProgram->reject('لم يتم اجتياز التقييم');
                if ($childProgram->child->guardian) {
                    $this->notificationService->reject($childProgram, $childProgram->rejection_reason, $input['note'])
                        ->to($childProgram->child->guardian)
                        ->notify()
                        ->email(new Rejected($childProgram));
                }
            }
            $this->notificationService->statusChanged($childProgram, $statusChange)
                ->notifyAdmins()
                ->emailAdmins(new StatusChanged($childProgram, $statusChange));
        }
        $evaluation->update([
            'form_id' => $input['form_id'],
            'note' => $input['note'],
            'date_1' => $input['date_1'] ?? null,
            'date_2' => $input['date_2'] ?? null,
            'date_3' => $input['date_3'] ?? null,
            'done' => $input['done'],
            'pass' => $input['pass'],
        ]);
        $evaluation->questions()->detach();
        $evaluation->questions()->attach($input['questions']);
        $evaluation->users()->sync($input['users'] ?? [Auth::id()]);
        if (isset($input['path'])) {
            $evaluation->attachment()->delete();
            $evaluation->attachment()->create([
                'path' => $input['path'],
                'name' => 'مرفق التقييم'
            ]);
        }
        return Response::success($evaluation->load(Evaluation::RELATIONS));
    }

    public function destroy(ChildProgram $childProgram): JsonResponse
    {
        $evaluation = $childProgram->evaluation;
        if (!$evaluation) {
            return Response::success(false);
        }
        return Response::success($evaluation->delete());
    }

    public function show(ChildProgram $childProgram): JsonResponse
    {
        $evaluation = $childProgram->evaluation;
        if (!$evaluation) {
            return Response::failure('common.evaluation_not_found');
        }
        return Response::success($evaluation->load(Evaluation::RELATIONS));
    }

    public function scheduleEvaluation(Request $request, ChildProgram $childProgram): JsonResponse
    {
        $input = $request->validate([
            'schedule' => 'required|array|size:3',
            'schedule.*.date' => 'required|date',
            'schedule.*.from' => 'required|date_format:H:i:s',
            'schedule.*.to' => 'required|date_format:H:i:s',
            'notify' => 'required|boolean'
        ]);
        $schedule = $input['schedule'];
        foreach ($schedule as $key => $day) {
            $schedule[$key]['day'] = Util::getDayName($day['date'], 'en');
            $schedule[$key]['day_ar'] = Util::getDayName($day['date'], 'ar');
        }
        $childProgram->update(['evaluation_schedule' => $schedule]);
        $childProgram->refresh();
        if ($input['notify']) {
            if ($childProgram->child->guardian) {
                $this->notificationService->scheduleEvaluation($childProgram)
                    ->to($childProgram->child->guardian)
                    ->notify()
                    ->sms();
            }
            $this->notificationService->scheduleEvaluation($childProgram, true)
                ->notifyAdmins()
                ->emailAdmins(new EvaluationScheduled($childProgram));
        }
        return Response::success($childProgram->load(ChildProgram::RELATIONS));
    }
}
