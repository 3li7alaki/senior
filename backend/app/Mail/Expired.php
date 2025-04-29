<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Expired extends Mailable
{
    use Queueable, SerializesModels;

    private ChildProgram $childProgram;
    private Child $child;
    private Program $program;
    private string $title = 'مرت سنتان على تقديم الطلب';

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

    public function build(): Expired
    {
        return $this->subject('مرت سنتان على تقديم الطلب')
            ->view('guardian.expired')
            ->with([
                'title' => $this->title,
                'child' => $this->child,
                'program' => $this->program,
                'childProgram' => $this->childProgram
            ]);
    }
}
