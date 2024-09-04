<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class PostViewed extends Notification
{
    use Queueable;

    private $post;

    /**
     * Create a new notification instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'slack', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

     // Data stored in the database
     public function toDatabase($notifiable)
     {
         return [
             'post_id' => $this->post->id,
             'title' => $this->post->title,
             'created_at' => $this->post->created_at,
         ];
     }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data array for notifications
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'created_at' => $this->post->created_at,
        ];
    }

    public function toSms($notifiable)
    {
        return "Your post #{$this->post->id} has been viewed.";
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content("Your post #{$this->post->id} has been viewed.");
            // ->attachment(function ($attachment) {
            //     $attachment->title('Post #'.$this->post->id, url('/posts/'.$this->post->id))
            //         ->fields([
            //             'Title' => '$'.$this->post->title,
            //             'Created At' => $this->post->created_at,
            //         ]);
            // });
    }
}
