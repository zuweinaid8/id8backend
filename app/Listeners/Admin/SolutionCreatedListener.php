<?php

namespace App\Listeners\Admin;

use App\Mail\Admin\SolutionCreated;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SolutionCreatedListener implements ShouldQueue
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
        $solution = $event->solution;

        $admins = User::query()->where('type', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin)->send(new SolutionCreated($solution));
        }
    }
}
