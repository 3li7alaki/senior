<?php

namespace App\Http\Controllers\Child;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Child\AdminDataFileRequest;
use App\Models\Child;
use App\Models\DataFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;

class AdminDataFileController extends Controller
{
    public function store(AdminDataFileRequest $request, Child $child): JsonResponse
    {
        $update = (bool)$child->dataFile;
        $input = $request->validated();
        if ($update) {
            $dataFile = DataFile::find($child->dataFile->id);
            $dataFile->update($input);
        } else {
            DataFile::query()->create(Arr::add($input, 'child_id', $child->id));
            $child->update(['data_file_needed' => false]);
        }
        return Response::success($child->dataFile);
    }

    public function update(AdminDataFileRequest $request, Child $child): JsonResponse
    {
        $input = $request->validated();
        $dataFile = DataFile::find($child->dataFile->id);
        $dataFile->update($input);
        $child->update(['data_file_needed' => false]);
        return Response::success($child->dataFile);
    }

    public function destroy(Child $child): JsonResponse
    {
        if (!$child->dataFile) {
            return Response::success([]);
        }
        $dataFile = DataFile::find($child->dataFile->id);
        return Response::success($dataFile->delete());
    }

    public function show(Child $child): JsonResponse
    {
        if (!$child->dataFile) {
            return Response::success([]);
        }
        return Response::success($child->dataFile);
    }
}
