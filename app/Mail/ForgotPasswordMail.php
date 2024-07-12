<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $username;
    private $token;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $username, $token)
    {
        $this->email = $email;
        $this->username = $username;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: trans('mail.forgot_password.subject'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content()
    {
        $verifyCodeUrl = config('app.user_url') . '/forgot-password?email=' . $this->email . '&token=' . $this->token;

        return new Content(
            view: 'mails.forgot-password',
            with: [
                'username' => $this->username,
                'verifyCodeUrl' => $verifyCodeUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
