<?php

namespace App\Listeners;

use App\Events\ProjectCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProjectCreatedListener
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
     * @param  \App\Events\ExampleEvent  $event
     * @return void
     */
    public function handle(ProjectCreatedEvent $event)
    {
        // Link the project with the user.
        $event->project->users()->attach($event->user->id);
    }
}
