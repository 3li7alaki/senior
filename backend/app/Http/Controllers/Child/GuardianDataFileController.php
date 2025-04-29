<?php

namespace App\Http\Controllers\Child;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Child\GuardianDataFileRequest;
use App\Mail\DataFileFilled;
use App\Models\Child;
use App\Models\DataFile;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;

class GuardianDataFileController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(GuardianDataFileRequest $request, Child $child): JsonResponse
    {
        $update = (bool)$child->dataFile;
        $input = $request->validated();
        if ($update) {
            $dataFile = DataFile::find($child->dataFile->id);
            $dataFile->update($input);
        } else {
            DataFile::query()->create(Arr::add($input, 'child_id', $child->id));
            $child->update(['data_file_needed' => false]);
            $this->notificationService->dataFileFilled($child)
                ->notifyAdmins()
                ->emailAdmins(new DataFileFilled($child));
        }
        return Response::success($child->dataFile);
    }

    public function update(GuardianDataFileRequest $request, Child $child): JsonResponse
    {
        $input = $request->validated();
        $dataFile = DataFile::find($child->dataFile->id);
        $dataFile->update($input);
        return Response::success($child->dataFile);
    }

    public function show(Child $child): JsonResponse
    {
        if (!$child->dataFile) {
            return Response::success([]);
        }
        return Response::success($child->dataFile);
    }
}
