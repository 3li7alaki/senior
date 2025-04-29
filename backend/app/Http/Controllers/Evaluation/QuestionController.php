<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class QuestionController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Question::all());
    }

    public function store(QuestionRequest $request): JsonResponse
    {
        $input = $request->validated();
        $question = Question::query()->create($input);
        return Response::success($question);
    }

    public function update(QuestionRequest $request, Question $question): JsonResponse
    {
        $input = $request->validated();
        if ($input['type'] === 'text') {
            $input['options'] = null;
        }
        $question->update($input);
        return Response::success($question);
    }

    public function destroy(Question $question): JsonResponse
    {
        if ($question->evaluations()->exists()) {
            return Response::failure('question.has_evaluations');
        }
        return Response::success($question->delete());
    }

    public function show(Question $question): JsonResponse
    {
        return Response::success($question);
    }
}
