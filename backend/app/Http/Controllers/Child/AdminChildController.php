<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Http\Requests\Child\AdminChildRequest;
use App\Models\Child;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class AdminChildController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Child::all()->load(Child::RELATIONS));
    }

    public function store(AdminChildRequest $request): JsonResponse
    {
        $input = $request->validated();
        $child = Child::query()->create($input);
        return Response::success($child->load(Child::RELATIONS));
    }

    public function update(AdminChildRequest $request, Child $child): JsonResponse
    {
        $child->update($request->validated());
        return Response::success($child->load(Child::RELATIONS));
    }

    public function destroy(Child $child): JsonResponse
    {
        return Response::success($child->delete());
    }

    public function show(Child $child): JsonResponse
    {
        return Response::success($child->load(Child::RELATIONS));
    }
}
