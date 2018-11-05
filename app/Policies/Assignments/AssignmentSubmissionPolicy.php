<?php

namespace App\Policies\Assignments;

use App\User;
use App\Models\Assignments\AssignmentSubmission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentSubmissionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->type == 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the assignment submission.
     *
     * @param  \App\User $user
     * @param  \App\Models\Assignments\AssignmentSubmission $assignmentSubmission
     * @return mixed
     * @throws AuthorizationException
     */
    public function delete(User $user, AssignmentSubmission $assignmentSubmission)
    {
        if (! $user->isStartup()) {
            throw new AuthorizationException('Unauthorized! Only startups can DELETE submissions');
        }

        return true;
    }
}
