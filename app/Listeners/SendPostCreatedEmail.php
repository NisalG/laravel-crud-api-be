<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostCreatedEmail;

use Throwable;

class SendPostCreatedEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostCreated $event): void
    {
        info("PostCreated event's SendPostCreatedEmail listner's >> handle() method called");

        // Send confirmation email to the customer
        Mail::to($event->post->user->email)->send(new PostCreatedEmail($event->post));
    }

    /**
     * Handle a job failure.
     */
    public function failed(PostCreated $event, Throwable $exception): void
    {
        // Handle the failure (e.g., log the error, notify admin, etc.)
        info('Failed to send post created email', [
            'post_id' => $event->post->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
