<?php

namespace App\Listeners;

use App\Events\UserHasSignedUp;
use App\Notifications\UserSignedUp;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyWhenSignedUp
{
    /**
     * @var User
     */
    private $user;

    /**
     * Create the event listener.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the event.
     *
     * @param  UserHasSignedUp  $event
     * @return void
     */
    public function handle(UserHasSignedUp $event)
    {
        /**
         * Handle the event.
         *
         * @param  UserHasSignedUp $event
         * @return void
         */

        $slackUser = config('slack.channels.signups');
        // Notify Admin
        $admin = $this->user->find(1);

        $admin->notify(new UserSignedUp($event->user, $slackUser));
    }
}
