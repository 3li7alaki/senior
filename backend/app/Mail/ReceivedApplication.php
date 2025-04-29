<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceivedApplication extends Mailable
{
    use Queueable, SerializesModels;

    private User $admin;
    private Program $program;
    private ChildProgram $childProgram;
    private string $title = 'تم وصول طلب جديد';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram)
    {
        $this->childProgram = $childProgram;
        $this->program = $childProgram->program;
    }

    public function setAdmin(User $admin): ReceivedApplication
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): ReceivedApplication
    {
        return $this->subject('تم وصول طلب جديد')
            ->view('admin.new_application')
            ->with([
                'title' => $this->title,
                'childProgram' => $this->childProgram,
                'program' => $this->program,
                'admin' => $this->admin
            ]);
    }
}
