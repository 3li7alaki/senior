<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\StatusChange;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DataFileFilled extends Mailable
{
    use Queueable, SerializesModels;

    private User $admin;
    private Child $child;
    private string $title = 'تم ملئ استمارة المعلومات الأساسية';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Child $child)
    {
        $this->child = $child;
    }

    public function setAdmin(User $admin): DataFileFilled
    {
        $this->admin = $admin;
        return $this;
    }

    public function build(): DataFileFilled
    {
        return $this->subject('تم تغيير حالة الطلبتم ملئ استمارة المعلومات الأساسية')
            ->view('admin.data_file_filled')
            ->with([
                'title' => $this->title,
                'child' => $this->child,
                'admin' => $this->admin
            ]);
    }
}
