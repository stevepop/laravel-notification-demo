<?php

namespace App\Notifications;

use Log;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserSignedUp extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    private $user;
    /**
     * @var
     */
    private $slackUser;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param $slackUser
     */
    public function __construct(User $user, $slackUser)
    {
        $this->user = $user;
        $this->slackUser = $slackUser;
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
            ->content('A new user has signed up!')
            ->from('Signups', ':trumpet:')
            ->to($this->slackUser)
            ->attachment(function ($attachment) {
                $attachment->title($this->user->name, url("/"))
                    ->fields([
                        'Date' => Carbon::now()->toDateTimeString(),
                        'Email' => $this->user->email,
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
            'user_id' => $this->user->id
        ];
    }
}
