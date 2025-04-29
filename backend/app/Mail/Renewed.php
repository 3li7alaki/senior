<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Renewed extends Mailable
{
    use Queueable, SerializesModels;

    private ChildProgram $childProgram;
    private User $admin;

    private string $title = 'تجديد الطلب بعد مرور سنتين';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram)
    {
        $this->childProgram = $childProgram;
    }

    public function setAdmin(User $admin): Renewed
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): Renewed
    {
        return $this->subject('تجديد الطلب بعد مرور سنتين')
            ->view('admin.renewed')
            ->with([
                'title' => $this->title,
                'childProgram' => $this->childProgram,
                'admin' => $this->admin
            ]);
    }
}
