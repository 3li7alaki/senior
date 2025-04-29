<?php

namespace App\Services;

use App\Enums\NotificationTypes;
use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Notification;
use App\Models\Status;
use App\Models\StatusChange;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    private string $title;
    private string $body;
    private string $type;
    private User $user;
    private $admins;
    private $canEmail;
    private $canSMS;

    private SMSService $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
        $this->canEmail = config('app.EMAIL_ENABLED');
        $this->canSMS = config('app.SMS_ENABLED');
        Log::log('info', 'NotificationService created');
        Log::log('info', 'Email enabled: ' . $this->canEmail);
        Log::log('info', 'SMS enabled: ' . $this->canSMS);
    }

    public function notify(): self
    {
        $this->user->notifications()->create([
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type
        ]);
        return $this;
    }

    public function notifyAdmins(): self
    {
        $this->admins = User::query()->where('type', 'admin')->orWhere('type', 'super_admin')->get();
        foreach ($this->admins as $admin) {
            $admin->notifications()->create([
                'title' => $this->title,
                'body' => $this->body,
                'type' => $this->type
            ]);
        }
        return $this;
    }

    public function sms(): self
    {
        if ($this->canSMS) {
            Log::log('info', 'Sending SMS to ' . $this->user->phone);
            $this->smsService->send($this->user->phone, $this->body);
        }
        return $this;
    }

    public function email(Mailable $mailable): self
    {
        if ($this->canEmail) {
            Log::log('info', 'Sending email to ' . $this->user->email);
            $mail = Mail::to($this->user->email)->send($mailable);
            if ($mail) {
                Log::log('info', 'Email sent successfully');
            } else {
                Log::log('error', 'Failed to send email');
            }
        }
        return $this;
    }

    public function emailAdmins(Mailable $mailable): self
    {
        if ($this->canEmail) {
            foreach ($this->admins as $admin) {
                Log::log('info', 'Sending email to ' . $admin->email);
                $mail = Mail::to($admin->email)->send($mailable->setAdmin($admin));
                $mailable->to = [];
                if ($mail) {
                    Log::log('info', 'Email sent successfully');
                } else {
                    Log::log('error', 'Failed to send email');
                }
            }
        }
        return $this;
    }


    public function to(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function body(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function scheduleEvaluation(ChildProgram $childProgram, bool $admin = false): self
    {
        $child = $childProgram->child;
        $schedule = $childProgram->evaluation_schedule;
        $program = $childProgram->program;

        $this->title('تم تحديد موعد التقييم المبدئي');

        if ($admin) {
            $this->body("عزيزي مدير النظام،
نفيدكم علماً بأن الطلب رقم {$childProgram->id} سيخضع إلى التقييم المبدئي في الأيام التالية:
اليوم الأول: {$schedule[0]['day_ar']} الموافق {$schedule[0]['date']} من الساعة {$schedule[0]['from']} إلى الساعة {$schedule[0]['to']}
اليوم الثاني: {$schedule[1]['day_ar']} الموافق {$schedule[1]['date']} من الساعة {$schedule[1]['from']} إلى الساعة {$schedule[1]['to']}
اليوم الثالث: {$schedule[2]['day_ar']} الموافق {$schedule[2]['date']} من الساعة {$schedule[2]['from']} إلى الساعة {$schedule[2]['to']}");
        } else {
            $this->body("عزيزي ولي الامر،
نحيطكم علماً بأنه تم تحديد موعد التقييم المبدئي لصاحب الطلب رقم {$childProgram->id} للالتحاق ببرنامج {$program->name} حيث سيتم التقييم على ثلاثة أيام:
يوم {$schedule[0]['day_ar']} {$schedule[0]['date']}
{$schedule[0]['from']} - {$schedule[0]['to']}
يوم {$schedule[1]['day_ar']} {$schedule[1]['date']}
{$schedule[1]['from']} - {$schedule[1]['to']}
يوم {$schedule[2]['day_ar']} {$schedule[2]['date']}
{$schedule[2]['from']} - {$schedule[2]['to']}
علماً بأن تكلفة التقييم المبدئي 20 ديناراً بحرينياً.
للاستفسارات الرجاء التواصل على 17467725.
");
        }
        $this->type(NotificationTypes::EVALUATION_SCHEDULED->value);
        return $this;
    }

    public function reject(ChildProgram $childProgram, string $reason, $note): self
    {
        $child = $childProgram->child;
        $program = $childProgram->program;

        $this->title('رفض الطلب');
        $this->body("عزيزي ولي الأمر،
بالإشارة إلى طلبكم رقم {$childProgram->id} لصاحب الهوية {$child->cpr} للالتحاق ببرنامج {$program->name}، فيؤسفنا إبلاغكم برفض طلبكم لعدم إستيفاء الشروط.
السبب: {$reason}
الملاحظات:
{$note}
لمزيد من المعلومات الرجاء التواصل على 17467725 / 39991480");
        $this->type(NotificationTypes::REJECTED->value);
        return $this;
    }

    public function expire(ChildProgram $childProgram, bool $admin = false): self
    {
        $child = $childProgram->child;
        $program = $childProgram->program;

        if ($admin) {
            $this->title('مرور سنتين على أحد الطلبات');
            $this->body("عزيزي مدير النظام،
نفيدكم علماً بأنه قد تم تلقي طلب جديد {$childProgram->id} للالتحاق ببرنامج {$program->name}.");
        } else {
            $this->title('انتهاء صلاحية الطلب');
            $this->body("عزيزي ولي الأمر،
بالإشارة إلى طلبكم رقم {$childProgram->id} الذي تم تلقيه في تاريخ {$childProgram->created_at} لصاحب الهوية {$child->cpr} للالتحاق ببرنامج {{$program->name}}، فنفيدكم علماً بأنه قد مرت سنتان على تقديم الطلب، يمكنكم تجديد الطلب من خلال الرابط التالي:
في حال عدم تجديد الطلب سيتم إلغاء الطلب بشكل تلقائي بعد شهر من تلقي الرسالة.
لمزيد من المعلومات الرجاء التواصل على 17467725 / 39991480
");
        }
        $this->type(NotificationTypes::EXPIRED->value);
        return $this;
    }

    public function expiredFully(ChildProgram $childProgram): self
    {
        $this->title('إلغاء الطلب بعد مرور سنتين');
        $this->body("عزيزي مدير النظام،
        نفيدكم علماً بأنه قد تم إلغاء الطلب رقم {$childProgram->id}لعدم تجديده من قبل ولي الأمر،");
        $this->type(NotificationTypes::EXPIRED->value);
        return $this;
    }

    public function accept(ChildProgram $childProgram): self
    {
        $child = $childProgram->child;
        $program = $childProgram->program;

        $this->title('قبول الطلب');
        $this->body("عزيزي ولي الأمر،
بالإشارة إلى طلبكم رقم {$childProgram->id} لصاحب الهوية {$child->cpr} للالتحاق ببرنامج {$program->name}، فيسرنا إبلاغكم  بأنه قد تم قبول طلبكم للالتحاق بالبرنامج.
لمزيد من المعلومات الرجاء التواصل على 17467725 / 39991480");
        $this->type(NotificationTypes::ACCEPTED->value);
        return $this;
    }

    public function wait(ChildProgram $childProgram): self
    {
        $child = $childProgram->child;
        $program = $childProgram->program;

        $this->title('تم اجتياز التقييم');
        $this->body("عزيزي ولي الأمر،
نفيدكم علماً بأنه تم تلقي طلبكم رقم {$childProgram->id}} لصاحب الهوية {$child->cpr} للالتحاق ببرنامج {$program->name}فنفيدكم علماً بأنه قد تم وضع طلبكم على قائمة الانتظار، وعليه الرجاء القيام بملئ استمارة المعلومات الأساسية.");
        $this->type(NotificationTypes::WAITING_LIST->value);
        return $this;
    }

    public function newApplication(ChildProgram $childProgram, bool $admin = false): self
    {
        $child = $childProgram->child;
        $program = $childProgram->program;
        if ($admin) {
            $this->title('تم وصول طلب جديد');
            $this->body("عزيزي مدير النظام،
نفيدكم علماً بأنه قد تم تلقي طلب جديد {$childProgram->id} للالتحاق ببرنامج {$program->name}.");
        } else {
            $this->title('تم تلقي طلبكم');
            $this->body("عزيزي ولي الأمر،
نفيدكم علماً بأنه تم تلقي طلبكم رقم {$childProgram->id} لصاحب الهوية {$child->cpr} للالتحاق ببرنامج {$program->name} في مركز تفاؤل للتربية الخاصة، علماً بأن الطلب صالح لمدة عامين من تاريخ التقديم.
سيتم التواصل معكم قريبا لإبلاغكم بحالة الطلب.
لمزيد من المعلومات الرجاء التواصل على 17467725 / 39991480");
        }
        $this->type(NotificationTypes::NEW_APPLICATION->value);
        return $this;
    }

    public function statusChanged(ChildProgram $childProgram, Model $statusChange): self
    {
        $this->title('تغيير حالة الطلب');
        $this->body("عزيزي مدير النظام،
نفيدكم علماً بأنه قد تم تغيير حالة الطلب رقم {$childProgram->id} من {$statusChange->oldStatus->name_ar} إلى {$statusChange->newStatus->name_ar}.");
        $this->type(NotificationTypes::STATUS_CHANGED->value);
        return $this;
    }

    public function dataFileFilled(Child $child): self
    {
        $this->title('تم ملئ استمارة المعلومات الأساسية');
        $this->body("عزيزي مدير النظام،
        نفيدكم علماً بأن ولي أمر الطفل صاحب الهوية {$child->cpr}قد قام بملئ استمارة المعلومات الأساسية.");
        $this->type(NotificationTypes::DATA_FILE_FILLED->value);
        return $this;
    }

    public function renewed(ChildProgram $childProgram): self
    {
        $this->title('تم تجديد الطلب');
        $this->body("عزيزي مدير النظام،
        نفيدكم علماً بأنه قد تم تجديد الطلب رقم {$childProgram->id}من قبل ولي الأمر،");
        $this->type(NotificationTypes::WAITING_LIST->value);
        return $this;
    }
}
