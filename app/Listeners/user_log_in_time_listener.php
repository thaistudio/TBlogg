<?php

namespace App\Listeners;

use App\ControllerHelper\Cache\UserCH;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class user_log_in_time_listener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        UserCH::login_at($event->user->id);
    }
}
