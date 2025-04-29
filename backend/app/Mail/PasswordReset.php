<?php

namespace App\Mail;

use App\Models\Child;
use App\Models\ChildProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    private string $url;
    private string $title = 'طلب إعادة تعيين كلمة المرور';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->url = config('app.url') . '/#/auth/reset-password?token=' . $token;
    }


    public function build(): PasswordReset
    {
        return $this->subject($this->title)
            ->view('password_reset')
            ->with([
                'title' => $this->title,
                'url' => $this->url,
            ]);
    }
}
