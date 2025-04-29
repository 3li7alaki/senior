<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiredForAdmins extends Mailable
{
    use Queueable, SerializesModels;

    private ChildProgram $childProgram;
    private User $admin;

    private string $title = 'مرت سنتان على تقديم الطلب';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram)
    {
        $this->childProgram = $childProgram;
    }

    public function setAdmin(User $admin): ExpiredForAdmins
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): ExpiredForAdmins
    {
        return $this->subject('مرت سنتان على تقديم الطلب')
            ->view('admin.expired')
            ->with([
                'title' => $this->title,
                'childProgram' => $this->childProgram,
                'admin' => $this->admin
            ]);
    }
}
