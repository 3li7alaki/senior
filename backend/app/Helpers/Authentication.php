<?php

namespace App\Helpers;

use App\Enums\UsersTypes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

trait   Authentication
{
    /**
     * Login Method
     *
     * @param  \Illuminate\Http\Request  $request
     *
     *
     * @return User
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only(['email', 'phone', 'password']), [
            'email' => ['required_without:phone'],
            'phone' => ['required_without:email'],
            'password' => ['required', 'string', 'min:4'],
        ]);
        if ($validator->fails()) {
            return Response::failure($validator->errors()->first(), 400);
        }
        if ($request->has('email')) {
            $user = User::query()->where('email', '=', $request->input('email'))->orWhere('phone', '=', $request->input('email'))->first();
        } else {
            $user = User::query()->where('phone', '=', $request->input('phone'))->first();
        }
        if (is_null($user)) {
            return Response::failure('authentication.no_user_found');
        }
        if (!Hash::check($request->input('password'), $user->password)) {
            return Response::failure('authentication.wrong_password');
        }

        switch ($user->type) {
            case UsersTypes::SUPERADMIN->value:
            {
                $user->tokens()->where('name', '=', UsersTypes::SUPERADMIN->value)->delete();
                $user['auth'] = $user->createToken(UsersTypes::SUPERADMIN->value);
                break;
            }
            case UsersTypes::ADMIN->value:
            {
                $user->tokens()->where('name', '=', UsersTypes::ADMIN->value)->delete();
                $user['auth'] = $user->createToken(UsersTypes::ADMIN->value);
                break;
            }
            case UsersTypes::GUARDIAN->value:
            {
                $user->tokens()->where('name', '=', UsersTypes::GUARDIAN->value)->delete();
                $user['auth'] = $user->createToken(UsersTypes::GUARDIAN->value);
                break;
            }
            default:
            {
                $user['auth'] = null;
                break;
            }
        }
        return Response::success($user->load('role'));
    }

/**
     * Register Method
     *
     * @param  \Illuminate\Http\Request  $request
     *
     *
     * @return User
     */

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only(['name', 'email', 'relation', 'phone', 'password', 'password_confirmation']), [
            'name' => ['required', 'string', 'min:4'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'relation' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return Response::failure($validator->errors()->first());
        }
        $user = User::query()->create(array_merge($request->except('password'), [
            'password' => Hash::make($request->input('password')),
            'type' => UsersTypes::GUARDIAN,
        ]));
        $user['auth'] = $user->createToken(UsersTypes::GUARDIAN->value);

        return Response::success($user->load('role'));
    }

    public function updateInfo(Request $request): JsonResponse
    {
        $user = Auth::user();
        $validator = Validator::make($request->only(['name', 'email', 'phone', 'password']), [
            'name' => ['required', 'string', 'min:4'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'unique:users,phone,' . $user->id],
            'current_password' => ['required', 'password', 'min:4', 'required_with:password'],
            'password' => ['nullable', 'password', 'min:4', 'confirmed', 'different:current_password'],
        ]);
        if ($validator->fails()) {
            return Response::failure($validator->errors()->first());
        }
        if ($request->has('password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return Response::failure('authentication.wrong_password');
            }
            $user->update(array_merge($request->except('password'), [
                'password' => Hash::make($request->input('password')),
            ]));
        } else {
            $user->update($request->except('password'));
        }
        return Response::success($user->load('role'));
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->where('name', '=', $user->type)->delete();
        return Response::success();
    }

    public function me(): JsonResponse
    {
        $user = Auth::user();
        return Response::success($user->load('role'));
    }
}
