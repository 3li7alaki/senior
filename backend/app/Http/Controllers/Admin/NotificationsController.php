<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = Notification::query()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return Response::success($notifications);
    }

    public function newNotifications(): JsonResponse
    {
        $notifications = Notification::query()
            ->where('user_id', Auth::id())
            ->where('seen', false)
            ->orderBy('created_at', 'desc')
            ->get();
        return Response::success($notifications);
    }

    public function markAsSeen(Notification $notification): JsonResponse
    {
        $notification->update(['seen' => true]);
        return Response::success($notification);
    }

    public function markAsUnseen(Notification $notification): JsonResponse
    {
        $notification->update(['seen' => false]);
        return Response::success($notification);
    }

    public function markAllAsSeen(): JsonResponse
    {
        Notification::query()
            ->where('user_id', Auth::id())
            ->update(['seen' => true]);
        return Response::success([]);
    }

    public function destroy(Notification $notification): JsonResponse
    {
        return Response::success($notification->delete());
    }

    public function show(Notification $notification): JsonResponse
    {
        $notification->update(['seen' => true]);
        return Response::success($notification);
    }
}
