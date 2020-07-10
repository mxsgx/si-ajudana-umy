<?php

namespace App\Policies;

use App\Lecturer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LecturerPolicy
{
    use HandlesAuthorization;

    /**
     * Filters authorization before all other authorization checks.
     *
     * @param User $user
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
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Lecturer $lecturer
     * @return mixed
     */
    public function view(User $user, Lecturer $lecturer)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Lecturer $lecturer
     * @return mixed
     */
    public function update(User $user, Lecturer $lecturer)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Lecturer $lecturer
     * @return mixed
     */
    public function delete(User $user, Lecturer $lecturer)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Lecturer $lecturer
     * @return mixed
     */
    public function restore(User $user, Lecturer $lecturer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Lecturer $lecturer
     * @return mixed
     */
    public function forceDelete(User $user, Lecturer $lecturer)
    {
        //
    }
}
