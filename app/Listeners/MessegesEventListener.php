<?php

namespace App\Listeners;

use App\Events\MessagesEvents;
use App\Messages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MessegesEventListener
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
     * @param  MessagesEvents  $event
     * @return void
     */
    public function handle(MessagesEvents $event)
    {
        Messages::create($event->param);
    }
}
