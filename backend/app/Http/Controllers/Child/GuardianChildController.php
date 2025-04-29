<?php

namespace App\Http\Controllers\Child;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Child\GuardianChildRequest;
use App\Models\Attachment;
use App\Models\Child;
use App\Models\Diagnose;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GuardianChildController extends Controller
{
    public function index(): JsonResponse
    {
        $guardian = Auth::user();
        $children = $guardian->children()->get()->load(Child::RELATIONS);
        return Response::success($children);
    }

    public function store(GuardianChildRequest $request): JsonResponse
    {
        $guardian = Auth::user();
        $input = $request->validated();
        $child = $guardian->children()->create($input);
        return Response::success($child->load(Child::RELATIONS));
    }

    public function update(GuardianChildRequest $request, Child $child): JsonResponse
    {
        $child->update($request->validated());
        return Response::success($child->load(Child::RELATIONS));
    }

    public function show(Child $child): JsonResponse
    {
        return Response::success($child->load(Child::RELATIONS));
    }
}
