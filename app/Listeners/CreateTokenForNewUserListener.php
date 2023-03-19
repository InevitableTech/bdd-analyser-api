<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Token;

class CreateTokenForNewUserListener implements ShouldQueue
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
    public function handle(UserCreatedEvent $event)
    {
        // Perhaps introduce a queue / message broker here.
        Token::create([
            'user_id' => $event->user->id,
            'description' => 'default',
            'type' => Token::TYPE_CONSOLE
        ]);
    }
}
