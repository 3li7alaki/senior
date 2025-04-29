<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all()->load('permissions'));
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $input = $request->validated();
        $role = Role::query()->create(['name' => $input['name']]);
        $role->permissions()->sync($input['permissions']);
        return Response::success($role->load('permissions'));
    }

    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        $input = $request->validated();
        $role->update(['name' => $input['name']]);
        $role->permissions()->sync($input['permissions']);
        return Response::success($role->load('permissions'));
    }

    public function destroy(Role $role): JsonResponse
    {
        if ($role->users()->count() > 0) {
            return Response::failure('common.cannot_delete_role');
        }
        return Response::success($role->delete());
    }

    public function show(Role $role): JsonResponse
    {
        return Response::success($role->load('permissions'));
    }
}
