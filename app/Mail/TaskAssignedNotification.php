<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskAssignedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('New Task Assigned')
                    ->view('emails.task-assigned')
                    ->with([
                        'taskName' => $this->task->name,
                        'projectName' => $this->task->project->name,
                    ]);
    }
}
