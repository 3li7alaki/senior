<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function guardianDashboard(): JsonResponse
    {
        $guardian = Auth::user();
        $dataFileNeeded = $guardian->children()->where('data_file_needed', 1)->get();
        $children = $guardian->children()->get();
        $evaluationScheduled = [];
        foreach ($children as $child) {
            foreach ($child->programs as $program) {
                $schedule = $program->child_program->evaluation_schedule;
                if (empty($schedule)) {
                    continue;
                }
                foreach ($schedule as $day) {
                    $date = Carbon::parse($day['date']);
                    if ($date->isAfter(Carbon::now())) {
                        $evaluationScheduled[] = [
                            'child' => $child->full_name,
                            'day' => $day
                        ];
                    }
                }
            }
        }
        return Response::success([
            'data_file_needed' => $dataFileNeeded,
            'evaluation_scheduled' => $evaluationScheduled,
        ]);
    }

    public function programsReport(Request $request): JsonResponse
    {
        $children = collect();
        $pID = $request->input('program_id');
        $sID = $request->input('status_id');
        if (!is_null($pID)) {
            $program = Program::find($pID);
            if ($program) {
                $children = $this->getProgramChildren($program, $sID);
            }
        } else {
            $programs = Program::all();
            foreach ($programs as $program) {
                $programChildren = $this->getProgramChildren($program, $sID);
                $children = $children->merge($programChildren);
            }
        }

        return Response::success($children);
    }

    function getProgramChildren($program, $statusId): Collection
    {
        $programChildren = !is_null($statusId) ? $program->children()->where('status_id', $statusId)->get() : $program->children()->get();
        $children = collect();
        foreach ($programChildren as $child) {
            $child['status'] = Status::query()->find($child->child_programs->status_id);
            $child['program'] = $program;
            $child['age_applied'] = Carbon::parse($child->child_programs->created_at)->diffInYears(Carbon::parse($child->birth_date));
            $children->push($child);
        }
        return $children;
    }
}
