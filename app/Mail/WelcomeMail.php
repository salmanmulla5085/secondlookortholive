<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $opr;
    public $user;
    public $subject;
    public $doctor_details;
    public $patient;
    public $app_details;
    public $otp;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($opr = null, $subject = null, $user = null, $doctor_details = null, $patient = null, $app_details = null, $otp = null, $reason = null)
    {
        $this->opr = $opr;
        $this->user = $user;
        $this->subject = $subject;
        $this->doctor_details = $doctor_details;
        $this->patient = $patient;
        $this->app_details = $app_details;
        $this->otp = $otp;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
