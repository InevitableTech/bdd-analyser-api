<?php

namespace App\Events;

use App\Models\Project;
use App\Models\User;

class ProjectCreatedEvent extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Project $project, public User $user)
    {
        //
    }
}
