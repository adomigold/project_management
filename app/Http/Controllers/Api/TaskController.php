<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssignedNotification;
use App\Mail\ProjectCompletedNotification;


class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(int $project)
    {
        $projects = Project::find($project);

        if (!$projects) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $search = request()->query('search');

        $tasks = $projects->tasks()
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                }
            })
            ->paginate();
        return $tasks;
    }


    public function store(TaskRequest $request, int $project)
    {
        $this->authorize('create', Task::class);

        $user = Auth::user();

        $projects = $user->projects()->find($project);
        $task = $projects->tasks()->create($request->all());

        $assignee = $task->assignee()->first();

        Mail::to($assignee->email)->queue(new TaskAssignedNotification($task));

        return $task;
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(TaskRequest $request, $project, $task)
    {
        $this->authorize('update', Task::class);

        $user = Auth::user();

        $projects = $user->projects()->find($project) ?? abort(404, 'Project not found');
        $task = $projects->tasks()->find($task) ?? abort(404, 'Task not found');
        $task->update($request->all());

        return $task;
    }

    public function destroy($project, $task)
    {
        $this->authorize('delete', Task::class);

        $user = Auth::user();

        $projects = $user->projects()->find($project) ?? abort(404, 'Project not found');
        $task = $projects->tasks()->find($task) ?? abort(404, 'Task not found');
        $task?->delete();

        return response()->json(['message' => 'Task deleted successfully'], 204);
    }

    public function updateStatus(Request $request, $task)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $user = Auth::user();
        $task = $user->tasks()->find($task) ?? abort(404, 'Task not found');

        $this->authorize('updateStatus', $task);

        $task->status = $request->status;
        $task->save();

        // Check if all tasks of the project are completed
        $project = $task->project;
        $completedTasks = $project->tasks()->where('status', 'Completed')->count();

        if ($completedTasks === $project->tasks()->count()) {
            $manager = $project->manager()->first();

            Mail::to($manager->email)->queue(new ProjectCompletedNotification($project));
        }
        
        return response()->json(['message' => 'Task status updated successfully'], 200);
    }
}
