<?php

namespace App\Http\Controllers\Evaluation;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\EFormRequest;
use App\Models\Form;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class FormController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Form::all()->load(Form::RELATIONS));
    }

    public function store(EFormRequest $request): JsonResponse
    {
        $input = $request->validated();
        $form = Form::query()->create($input);
        if (isset($input['path'])) {
            $form->attachment()->create($request->only(['path', 'name']));
        }
        $form->questions()->sync($input['questions']);
        return Response::success($form->load(Form::RELATIONS));
    }

    public function update(EFormRequest $request, Form $form): JsonResponse
    {
        $input = $request->validated();
        $form->update($input);
        $form->questions()->sync($input['questions']);
        if (isset($input['path'])) {
            $form->attachment()->updateOrCreate([], $request->only(['path', 'name']));
        }
        return Response::success($form->load(Form::RELATIONS));
    }

    public function destroy(Form $form): JsonResponse
    {
        return Response::success($form->delete());
    }

    public function show(Form $form): JsonResponse
    {
        return Response::success($form->load(Form::RELATIONS));
    }
}
