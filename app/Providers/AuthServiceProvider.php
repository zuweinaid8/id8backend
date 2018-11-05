<?php

namespace App\Providers;

use App\Models\Assignments\Assignment;
use App\Models\Assignments\AssignmentGrade;
use App\Models\Assignments\AssignmentSubmission;
use App\Models\Calendars\Calendar;
use App\Models\Calendars\Event;
use App\Policies\Assignments\AssignmentGradePolicy;
use App\Policies\Assignments\AssignmentPolicy;
use App\Policies\Assignments\AssignmentSubmissionPolicy;
use App\Policies\Calendars\CalendarPolicy;
use App\Policies\Calendars\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Assignment::class => AssignmentPolicy::class,
        AssignmentSubmission::class => AssignmentSubmissionPolicy::class,
        AssignmentGrade::class => AssignmentGradePolicy::class,

        Calendar::class => CalendarPolicy::class,
        Event::class => EventPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
