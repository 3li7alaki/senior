<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApplication extends Mailable
{
    use Queueable, SerializesModels;

    private ChildProgram $childProgram;
    private Child $child;
    private Program $program;
    private string $title = 'تم تلقي طلبكم';

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

    public function build(): NewApplication
    {
        return $this->subject('تم تلقي طلبكم')
            ->view('guardian.new_application')
            ->with([
                'title' => $this->title,
                'child' => $this->child,
                'program' => $this->program,
                'childProgram' => $this->childProgram
            ]);
    }
}
