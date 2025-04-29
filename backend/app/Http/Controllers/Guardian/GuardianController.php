<?php

namespace App\Http\Controllers\Guardian;

use App\Enums\UsersTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guardian\AdminGuardianRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class GuardianController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(User::query()->where('type', 'guardian')->get());
    }

    public function store(AdminGuardianRequest $request): JsonResponse
    {
        $input = $request->validated();
        $input['type'] = 'guardian';
        $input['password'] = Hash::make($input['password']);
        $guardian = User::query()->create($input);
        return Response::success($guardian);
    }

    public function update(AdminGuardianRequest $request, User $guardian): JsonResponse
    {
        $input = $request->validated();
        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }
        if (is_null($input['password'])) {
            unset($input['password']);
        }
        $guardian->update($input);
        return Response::success($guardian->load('children'));
    }

    public function destroy(User $guardian): JsonResponse
    {
        if ($guardian->type === UsersTypes::GUARDIAN->value) {
            return Response::success($guardian->delete());
        } else {
            return Response::failure('common.cannot_delete_admin');
        }
    }

    public function show(User $guardian): JsonResponse
    {
        return Response::success($guardian->load('children'));
    }
}
