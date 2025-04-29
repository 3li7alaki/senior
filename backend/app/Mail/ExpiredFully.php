<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiredFully extends Mailable
{
    use Queueable, SerializesModels;

    private ChildProgram $childProgram;
    private User $admin;

    private string $title = 'إلغاء الطلب بعد مرور سنتين';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram)
    {
        $this->childProgram = $childProgram;
    }

    public function setAdmin(User $admin): ExpiredFully
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): ExpiredFully
    {
        return $this->subject('إلغاء الطلب بعد مرور سنتين')
            ->view('admin.expired_fully')
            ->with([
                'title' => $this->title,
                'childProgram' => $this->childProgram,
                'admin' => $this->admin
            ]);
    }
}
