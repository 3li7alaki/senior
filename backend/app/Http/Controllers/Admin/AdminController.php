<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(User::query()->where('type', 'admin')->get()->load('role'));
    }

    public function store(AdminRequest $request): JsonResponse
    {
        $input = $request->validated();
        $input['type'] = 'admin';
        $input['password'] = Hash::make($input['password']);

        $admin = User::query()->create($input);

        return Response::success($admin->load('role'));
    }

    public function update(AdminRequest $request, User $admin): JsonResponse
    {
        $input = $request->validated();
        if (!is_null($input['password']) && $input['password'] != '')
            $input['password'] = Hash::make($input['password']);
        else
            unset($input['password']);

        $admin->update($input);
        $admin->refresh();

        return Response::success($admin->load('role'));
    }

    public function destroy(User $admin): JsonResponse
    {
        return Response::success($admin->delete());
    }

    public function show(User $admin): JsonResponse
    {
        return Response::success($admin->load('role'));
    }
}
