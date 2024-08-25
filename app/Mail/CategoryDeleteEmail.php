<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class CategoryDeleteEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    // public $attachments;

    /**
     * Create a new message instance.
     */
    // public function __construct($emailData, $attachments)
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
        // $this->attachments = $attachments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Category Delete Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.categoryDeleteEmail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->attachments as $filePath) {
            $attachments[] = Attachment::fromStorage($filePath);
            // $attachments[] = Attachment::fromPath(Storage::path($filePath)); // using fromPath() instead of fromStorage()
        }
        
        return $attachments;
    }
}
