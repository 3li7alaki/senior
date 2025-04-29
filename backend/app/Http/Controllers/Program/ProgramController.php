<?php

namespace App\Http\Controllers\Program;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Program\ProgramRequest;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ProgramController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Program::all());
    }

    public function store(ProgramRequest $request): JsonResponse
    {
        $input = $request->validated();
        $program = Program::query()->create($input);
        $program->diagnoses()->sync($input['diagnoses'] ?? []);
        $program->attachments()->createMany($input['attachments'] ?? []);
        return Response::success($program->load(Program::RELATIONS));
    }

    public function update(ProgramRequest $request, Program $program): JsonResponse
    {
        $input = $request->validated();
        $program->update($input);
        $program->diagnoses()->sync($input['diagnoses'] ?? []);
        if (count($program->attachments) + count($input['attachments'] ?? []) > 3) {
            return Response::failure('attachments.max');
        }
        $program->attachments()->createMany($input['attachments'] ?? []);
        return Response::success($program->load(Program::RELATIONS));
    }

    public function destroy(Program $program): JsonResponse
    {
        return Response::success($program->delete());
    }

    public function show(Program $program): JsonResponse
    {
        return Response::success($program->load(Program::RELATIONS));
    }
}
