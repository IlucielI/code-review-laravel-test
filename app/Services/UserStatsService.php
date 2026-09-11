<?php

namespace App\Services;

use App\Models\User;

class UserStatsService
{
    public function postsPerUser(): array
    {
        $users = User::all();
        $result = [];

        foreach ($users as $user) {
            $result[$user->id] = $user->posts->count();
        }

        return $result;
    }
}
