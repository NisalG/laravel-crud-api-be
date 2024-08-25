<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event): void {
        info('User Logged In: ', ['user_id' => $event->user->id]);
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void {
        info('User Logged Out: ', ['user_id' => $event->user->id]);
    }

    /**
     * Method 1 - Register the listeners for the subscriber:
     * 
     */
    // public function subscribe(Dispatcher $events): void
    // {

    //     $events->listen(
    //         Login::class,
    //         [UserEventSubscriber::class, 'handleUserLogin']
    //     );

    //     $events->listen(
    //         Logout::class,
    //         [UserEventSubscriber::class, 'handleUserLogout']
    //     );
    // }

    /**
     * Method 2 - Register the listeners for the subscriber: If your event listener methods are within the subscriber itself, you can return an array of events and corresponding method names from the subscriber's subscribe method.
     * Laravel will then automatically determine the subscriber's class name when registering the event listeners.
     * 
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
        ];
    }
}
