<?php

namespace App\Listeners;

use App\Events\NewPostCreated;
use App\Notifications\PostCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyWhenNewPostCreated
{
    /**
     * @var Post
     */
    private $post;

    /**
     * Create the event listener.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        //
        $this->post = $post;
    }

    /**
     * Handle the event.
     *
     * @param  NewPostCreated  $event
     * @return void
     */
    /**
     * Handle the event.
     *
     * @param  NewPostCreated  $event
     * @return void
     */
    public function handle(NewPostCreated $event)
    {
        /**
         * Handle the event.
         *
         * @param  NewPostCreated $event
         * @return void
         */

        $slackUser = config('slack.channels.signups');
        // Notify Admin
        $admin = User::find(1);

        $admin->notify(new PostCreated($event->user, $slackUser));
    }
}
