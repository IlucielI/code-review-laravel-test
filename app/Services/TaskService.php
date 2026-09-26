<?php

namespace App\Services;

use App\Models\User;

class TaskService
{
    /**
     * Get task summaries for all users.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getUserTaskSummaries(): array
    {
        $users = User::all();

        $summaries = [];
        foreach ($users as $user) {
            $tasks = $user->tasks;

            $summaries[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'total_tasks' => $tasks->count(),
            ];
        }

        return $summaries;
    }
}
