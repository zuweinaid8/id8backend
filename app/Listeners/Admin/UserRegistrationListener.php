<?php

namespace App\Listeners\Admin;

use App\Mail\Admin\UserRegistered;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UserRegistrationListener implements ShouldQueue
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
        logger('User Registration event fired');

        $user = $event->user;
        $admins = User::query()->where('type', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin)->send(new UserRegistered($user));
        }
    }
}
