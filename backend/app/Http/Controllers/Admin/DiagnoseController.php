<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiagnoseRequest;
use App\Http\Requests\Admin\NameRequest;
use App\Models\Diagnose;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class DiagnoseController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Diagnose::query()->where('type', '=', 'preset')->get());
    }

    public function store(DiagnoseRequest $request): JsonResponse
    {
        $input = array_merge($request->validated(), ['type' => 'preset']);
        $diagnose = Diagnose::query()->create($input);
        return Response::success($diagnose);
    }

    public function update(DiagnoseRequest $request, Diagnose $diagnose): JsonResponse
    {
        $diagnose->update($request->validated());
        return Response::success($diagnose->refresh());
    }

    public function destroy(Diagnose $diagnose): JsonResponse
    {
        return Response::success($diagnose->delete());
    }

    public function show(Diagnose $diagnose): JsonResponse
    {
        return Response::success($diagnose);
    }
}
