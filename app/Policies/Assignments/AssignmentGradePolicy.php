<?php

namespace App\Policies\Assignments;

use App\User;
use App\Models\Assignments\AssignmentGrade;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentGradePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->type == 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the assignment grade.
     *
     * @param  \App\User $user
     * @param  \App\Models\Assignments\AssignmentGrade $assignmentGrade
     * @return mixed
     * @throws AuthorizationException
     */
    public function delete(User $user, AssignmentGrade $assignmentGrade)
    {
        if (! $user->isMentor()) {
            throw new AuthorizationException('Unauthorized! Only Mentors can DELETE grades');
        }
        return true;
    }
}
