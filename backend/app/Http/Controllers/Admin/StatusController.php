<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NameRequest;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class StatusController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Status::all()->loadCount('childPrograms'));
    }

    public function store(NameRequest $request): JsonResponse
    {
        $status = Status::query()->create(array_merge($request->validated(), ['type' => Statuses::OTHER]));
        return Response::success($status);
    }

    public function update(NameRequest $request, Status $status): JsonResponse
    {
        $status->update($request->validated());
        return Response::success($status->refresh());
    }

    public function destroy(Status $status): JsonResponse
    {
        if ($status->type != Statuses::OTHER->value || $status->isUsed()) {
            return Response::failure('status.cant_delete');
        }
        return Response::success($status->delete());
    }

    public function show(Status $status): JsonResponse
    {
        return Response::success($status);
    }
}
