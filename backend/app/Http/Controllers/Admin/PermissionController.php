<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Permission::all()->groupBy('group'));
    }
}
