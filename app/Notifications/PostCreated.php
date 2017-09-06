<?php

namespace App\Notifications;

use App\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostCreated extends Notification
{
    use Queueable;
    /**
     * @var Post
     */
    private $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/');

        return (new MailMessage)
            ->greeting('Hello!')
            ->line("A new user has signed up!")
            ->action('Login here', $url)
            ->line('Your friendly Notifier!');
    }


    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content('A new Post has been created!')
            ->from('user_events', ':trumpet:')
            ->to($this->slackUser)
            ->attachment(function ($attachment) {
                $attachment->title($this->post->title, url("/"))
                    ->fields([
                        'Date' => Carbon::now()->toDateTimeString(),
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
