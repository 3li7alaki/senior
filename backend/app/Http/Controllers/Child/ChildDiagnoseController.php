<?php

namespace App\Http\Controllers\Child;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Child\ChildDiagnoseRequest;
use App\Models\Attachment;
use App\Models\Child;
use App\Models\ChildDiagnose;
use App\Models\Diagnose;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ChildDiagnoseController extends Controller
{
    public function index(Child $child): JsonResponse
    {
        return Response::success($child->diagnoses);
    }

    public function store(ChildDiagnoseRequest $request, Child $child): JsonResponse
    {
        $input = array_merge($request->validated(), ['child_id' => $child->id, 'type' => 'other']);
        if (Diagnose::query()->where('id', $input['diagnose_id'])->doesntExist()) {
            $diagnoses = $child->diagnoses->pluck('name')->toArray();
            if (in_array($input['name'], $diagnoses)) {
                return Response::failure('common.diagnose_exists');
            }
            $input['diagnose_id'] = Diagnose::query()->create($input)->id;
        }
        $childDiagnose = ChildDiagnose::query()->create($input);
        $childDiagnose->save();
        if (isset($input['attachments'])) {
            foreach ($input['attachments'] as $attachment) {
                Attachment::query()->create([
                    'attachable_id' => $childDiagnose->id,
                    'attachable_type' => ChildDiagnose::class,
                    'path' => $attachment,
                    'name' => $attachment->getClientOriginalName(),
                ]);
            }
        }
        return Response::success($childDiagnose->load('attachments'));
    }

    public function update(ChildDiagnoseRequest $request, Child $child, ChildDiagnose $childDiagnose): JsonResponse
    {
        $input = $request->validated();
        $childDiagnose->update($input);
        if (isset($input['attachments'])) {
            foreach ($input['attachments'] as $attachment) {
                Attachment::query()->create([
                    'attachable_id' => $childDiagnose->id,
                    'attachable_type' => ChildDiagnose::class,
                    'path' => $attachment,
                    'name' => $attachment->getClientOriginalName(),
                ]);
            }
        }
        return Response::success($childDiagnose->load('attachments'));
    }

    public function destroy(Child $child, ChildDiagnose $childDiagnose): JsonResponse
    {
        return Response::success($childDiagnose->delete());
    }

    public function show(Child $child, ChildDiagnose $childDiagnose): JsonResponse
    {
        return Response::success($childDiagnose->load('attachments'));
    }
}
