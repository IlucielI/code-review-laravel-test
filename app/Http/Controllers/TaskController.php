<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * Display a listing of user task summaries.
     *
     * @return JsonResponse
     */
    public function userSummaries(): JsonResponse
    {
        $summaries = $this->taskService->getUserTaskSummaries();

        return response()->json([
            'status' => 'success',
            'data' => $summaries,
        ]);
    }
}
