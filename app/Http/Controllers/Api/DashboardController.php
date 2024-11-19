<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $cacheKey = 'dashboard_data';
        $cacheDuration = 1; // Cache duration in minutes

        $dashboardData = cache()->remember($cacheKey, $cacheDuration, function () {
            return [
                'total_projects' => Project::count(),
                'total_tasks' => Task::count(),
                'tasks_by_status' => Task::groupBy('status')->selectRaw('status, count(*) as count')->get(),
                'total_users' => User::count(),
            ];
        });

        return response()->json($dashboardData);
    }
}
