<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $this->isManagerial($user) || (int) $task->assigned_to === (int) $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isManagerial($user) || (int) $task->assigned_to === (int) $user->id;
    }

    public function updateStatus(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'employee'], true);
    }

    public function bulkManage(User $user): bool
    {
        return $this->isManagerial($user);
    }

    private function isManagerial(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager'], true);
    }
}

