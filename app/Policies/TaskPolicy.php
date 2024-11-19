<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
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
    public function update(User $user, Task $task)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task)
    {
        return in_array($user->roles()->first()->name, ['Admin', 'Manager']);
    }

    public function updateStatus(User $user, Task $task)
    {
        return $user->id === $task->assignee_id;
    }
}
