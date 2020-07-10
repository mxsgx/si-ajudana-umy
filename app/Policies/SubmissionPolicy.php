<?php

namespace App\Policies;

use App\Submission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Filters authorization before all other authorization checks.
     *
     * @param  User  $user
     * @param $ability
     * @return mixed
     */
    public function before($user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return isset($user->roles[$user->role]);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function view(User $user, Submission $submission)
    {
        return isset($user->roles[$user->role]) || $submission->lecturer_id === $user->lecturer_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === 'lecturer';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function update(User $user, Submission $submission)
    {
        return $user->role === 'lecturer' && $submission->lecturer_id === $user->lecturer_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function delete(User $user, Submission $submission)
    {
        return $user->role === 'lecturer' && $submission->lecturer_id === $user->lecturer_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function restore(User $user, Submission $submission)
    {
        return $user->role === 'lecturer' && $submission->lecturer_id === $user->lecturer_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function forceDelete(User $user, Submission $submission)
    {
        return $user->role === 'lecturer' && $submission->lecturer_id === $user->lecturer_id;
    }

    /**
     * Determine whether the user can authorize the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function authorize(User $user, Submission $submission)
    {
        if ($user->role !== 'head-of-program-study') {
            return false;
        }

        return $user->study_id === $submission->lecturer->study_id;
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function approve(User $user, Submission $submission)
    {
        if ($user->role !== 'dean') {
            return false;
        }

        return $user->faculty_id === $submission->lecturer->study->faculty_id;
    }

    /**
     * Determine whether the user can reject the model.
     *
     * @param  User  $user
     * @param  Submission  $submission
     *
     * @return mixed
     */
    public function reject(User $user, Submission $submission)
    {
        if ($user->role !== 'dean') {
            return false;
        }

        return $user->faculty_id === $submission->lecturer->study->faculty_id;
    }
}
