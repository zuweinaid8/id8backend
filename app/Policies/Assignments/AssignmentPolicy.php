<?php

namespace App\Policies\Assignments;

use App\User;
use App\Models\Assignments\Assignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->type == 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the assignment.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Assignments\Assignment  $assignment
     * @return mixed
     */
    public function delete(User $user, Assignment $assignment)
    {
        return $user->isMentor() &&
            ($assignment->mentorship->mentor_id == $user->id);
    }
}
