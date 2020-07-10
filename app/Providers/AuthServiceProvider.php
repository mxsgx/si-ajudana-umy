<?php

namespace App\Providers;

use App\Activity;
use App\Attachment;
use App\Category;
use App\Faculty;
use App\Financial;
use App\Lecturer;
use App\Participant;
use App\Policies\ActivityPolicy;
use App\Policies\AttachmentPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\FacultyPolicy;
use App\Policies\FinancialPolicy;
use App\Policies\LecturerPolicy;
use App\Policies\ParticipantPolicy;
use App\Policies\StudyPolicy;
use App\Policies\SubmissionPolicy;
use App\Policies\UserPolicy;
use App\Study;
use App\Submission;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Study::class => StudyPolicy::class,
        Faculty::class => FacultyPolicy::class,
        Lecturer::class => LecturerPolicy::class,
        Activity::class => ActivityPolicy::class,
        Category::class => CategoryPolicy::class,
        Financial::class => FinancialPolicy::class,
        Submission::class => SubmissionPolicy::class,
        Attachment::class => AttachmentPolicy::class,
        Participant::class => ParticipantPolicy::class,
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
