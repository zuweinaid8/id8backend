<?php

namespace App\Policies\Calendars;

use App\User;
use App\Models\Calendars\Event;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @param  \App\User $user
     * @param  \App\Models\Calendars\Event $event
     * @return mixed
     * @throws AuthorizationException
     */
    public function delete(User $user, Event $event)
    {
        $isOwner =  ($event->owner_id == $user->id);

        if (! $isOwner) {
            throw new AuthorizationException('Unauthorized! Only owner of an event can delete it.');
        }

        return true;
    }
}
