<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WaitingList extends Mailable
{
    use Queueable, SerializesModels;

    private ChildProgram $childProgram;
    private Child $child;
    private Program $program;
    private string $title = 'تغيير حالة الطلب إلى على قائمة الانتظار';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChildProgram $childProgram)
    {
        $this->childProgram = $childProgram;
        $this->child = $childProgram->child;
        $this->program = $childProgram->program;
    }

    public function build(): WaitingList
    {
        return $this->subject('تغيير حالة الطلب إلى على قائمة الانتظار')
            ->view('guardian.waiting_list')
            ->with([
                'title' => $this->title,
                'child' => $this->child,
                'program' => $this->program,
                'childProgram' => $this->childProgram
            ]);
    }
}
