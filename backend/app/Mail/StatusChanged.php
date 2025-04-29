<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\StatusChange;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    private User $admin;
    private Model $statusChange;
    private ChildProgram $childProgram;
    private string $title = 'تم تغيير حالة الطلب';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram, Model $statusChange)
    {
        $this->childProgram = $childProgram;
        $this->statusChange = $statusChange;
    }

    public function setAdmin(User $admin): StatusChanged
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): StatusChanged
    {
        return $this->subject('تم تغيير حالة الطلب')
            ->view('admin.status_changed')
            ->with([
                'title' => $this->title,
                'childProgram' => $this->childProgram,
                'statusChange' => $this->statusChange,
                'admin' => $this->admin
            ]);
    }
}
