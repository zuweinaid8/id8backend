<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Broadcast;
use App\Models\Messages\Thread;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('threads.{threadId}', function ($user, $threadId) {
    return Thread::query()
        ->where('threads.id', '=', $threadId)
        ->whereHas('participants', function ($q) use($user) {
            return $q->where('users.id', '=', $user->id);
        })
        ->exists();
});
