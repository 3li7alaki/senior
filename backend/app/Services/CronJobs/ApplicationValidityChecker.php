<?php

namespace App\Services\CronJobs;

use App\Mail\Expired;
use App\Mail\ExpiredForAdmins;
use App\Mail\ExpiredFully;
use App\Models\ChildProgram;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApplicationValidityChecker
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function run()
    {
        try {
            $programs = ChildProgram::query()
                ->whereHas('status', function ($query) {
                    $query->where('type', 'waiting');
                });
            foreach ($programs as $program) {
                if (Carbon::parse($program->waiting_at)->diffInYears() >= 2) {
                    $program->expire('مرت سنتان على تقديم الطلب');
                    if ($program->child->guardian) {
                        $this->notificationService->expire($program)
                            ->to($program->child->guardian)
                            ->notify()
                            ->email(new Expired($program));
                    }
                    $this->notificationService->expire($program, true)
                        ->notifyAdmins()
                        ->emailAdmins(new ExpiredForAdmins($program));
                }
            }
            $programs = ChildProgram::query()
                ->whereHas('status', function ($query) {
                    $query->where('type', 'expired');
                });
            foreach ($programs as $program) {
                if (Carbon::parse($program->expired_at)->diffInMonths() >= 1) {
                    $program->reject('لم يتم تجديد الطلب لمدة اسبوعين');
                    $this->notificationService->expiredFully($program)
                        ->notifyAdmins()
                        ->emailAdmins(new ExpiredFully($program));
                }
            }
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }
    }
}
