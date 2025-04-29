<?php

namespace App\Http\Controllers;

use App\Helpers\Authentication;
use App\Mail\PasswordReset;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, Authentication;

    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only(['email']), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return Response::failure($validator->errors()->first(), 400);
        }

        $user = User::query()->where('email', '=', $request->input('email'))->first();

        if (is_null($user)) {
            return Response::failure('authentication.no_user_found');
        }

        DB::table('password_reset_tokens')->where('email', '=', $user->email)->delete();

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $this->notificationService
            ->to($user)
            ->email(new PasswordReset($token));

        return Response::success(__('authentication.password_reset_email_sent'));
    }

    public function forgotPasswordReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only(['token', 'password', 'password_confirmation']), [
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return Response::failure($validator->errors()->first(), 400);
        }

        $passwordReset = DB::table('password_reset_tokens')->where('token', '=', $request->input('token'))->first();

        if (is_null($passwordReset)) {
            return Response::failure('authentication.invalid_token');
        }

        $user = User::query()->where('email', '=', $passwordReset->email)->first();

        if (is_null($user)) {
            return Response::failure('authentication.no_user_found');
        }

        $user->update(['password' => Hash::make($request->input('password'))]);

        DB::table('password_reset_tokens')->where('email', '=', $user->email)->delete();

        return Response::success(__('authentication.password_reset_success'));
    }
}
