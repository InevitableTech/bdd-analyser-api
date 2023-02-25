<?php

namespace App\Events;

use App\Models\User;

class UserCreatedEvent extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public User $user)
    {
        //
    }
}
