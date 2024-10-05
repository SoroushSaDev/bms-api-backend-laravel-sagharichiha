<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;

    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build(): VerifyEmail
    {
        return $this->view('emails.verify')->with([
            'code' => $this->code,
        ]);
    }
}
