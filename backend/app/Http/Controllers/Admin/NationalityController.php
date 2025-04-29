<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NameRequest;
use App\Models\Nationality;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NationalityController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Nationality::query()->get());
    }

    public function store(NameRequest $request): JsonResponse
    {
        $nationality = Nationality::query()->create($request->validated());
        return Response::success($nationality);
    }

    public function update(NameRequest $request, Nationality $nationality): JsonResponse
    {
        $nationality->update($request->validated());
        return Response::success($nationality->refresh());
    }

    public function destroy(Nationality $nationality): JsonResponse
    {
        return Response::success($nationality->delete());
    }

    public function show(Nationality $nationality): JsonResponse
    {
        return Response::success($nationality->load('children'));
    }
}
