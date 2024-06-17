<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;
    public $name;
    public $logoPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($resetLink, $name, $logoPath)
    {
        $this->resetLink = $resetLink;
        $this->name = $name;
        $this->logoPath = $logoPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('reset-password-emailtemplate')
                    ->subject('Reset Your Account Password')
                    ->with([
                        'resetLink' => $this->resetLink,
                        'name' => $this->name,
                        'logoPath' => $this->logoPath,
                    ]);
    }
}
