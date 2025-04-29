<?php

namespace App\Http\Middleware;


use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission): mixed
    {
        $user = $request->user();
        if (User::isSuperAdmin($user)) {
            return $next($request);
        }
        $permissions = $user->role->permissions->pluck('name');
        if ($permissions->contains($permission)) {
            return $next($request);
        }

        return Response::failure('authentication.not_authorized', 403);
    }
}
