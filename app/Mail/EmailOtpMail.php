<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $email;

    /**
     * Create a new message instance.
     */
    public function __construct(string $email, string $otp)
    {
        $this->email = $email;
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SYMCARD 2026 – Email Verification Code'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.email-otp',
            with: [
                'otp' => $this->otp,
                'email' => $this->email,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
