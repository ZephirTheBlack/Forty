<?php

namespace App\Listeners;

use App\Events\Modelliked;
use App\Notifications\NewLikeNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLikeNotification
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
     * @param  Modelliked  $event
     * @return void
     */
    public function handle(Modelliked $event)
    {
       $event->model->user->notify(
           new NewLikeNotification($event->model,$event->likeSender)
       );
    }
}
