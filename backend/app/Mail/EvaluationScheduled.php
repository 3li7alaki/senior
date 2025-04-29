<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationScheduled extends Mailable
{
    use Queueable, SerializesModels;

    private ?User $admin = null;
    private ChildProgram $childProgram;
    private array $schedule;

    private string $title = 'تم تحديد موعد التقييم المبدئي';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram)
    {
        $this->childProgram = $childProgram;
        $this->schedule = $childProgram->evaluation_schedule;
    }

    public function setAdmin(User $admin): EvaluationScheduled
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): EvaluationScheduled
    {
        return $this->subject('تم تحديد موعد التقييم المبدئي')
            ->view('admin.evaluation_scheduled')
            ->with([
                'title' => $this->title,
                'childProgram' => $this->childProgram,
                'schedule' => $this->schedule,
                'admin' => $this->admin
            ]);
    }
}
