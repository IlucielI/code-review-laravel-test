<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    /**
     * Get user tasks with clean eager loading (prevents N+1)
     */
    public function getUserTasks(int $userId): array
    {
        Log::info("Fetching tasks for user: {$userId}");

        return DB::table('tasks')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get user task count
     */
    public function getUserTaskCount(User $user): int
    {
        return DB::table('tasks')
            ->where('user_id', $user->id)
            ->count();
    }
}
