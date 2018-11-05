<?php

namespace App\Policies\Calendars;

use App\User;
use App\Models\Calendars\Calendar;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
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
     * Determine whether the user can delete the calendar.
     *
     * @param  \App\User $user
     * @param  \App\Models\Calendars\Calendar $calendar
     * @return mixed
     * @throws AuthorizationException
     */
    public function delete(User $user, Calendar $calendar)
    {
        $isOwner =  ($calendar->owner_id == $user->id);

        if (! $isOwner) {
            throw new AuthorizationException('Unauthorized! Only owner of a Calendar can delete it.');
        }

        return true;
    }
}
