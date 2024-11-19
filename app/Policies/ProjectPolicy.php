<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']) && $project->manager_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']) && $project->manager_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']) && $project->manager_id === $user->id;
    }
}