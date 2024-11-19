<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectCompletedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function build()
    {
        return $this->subject('Project Marked as Completed')
                    ->view('emails.project-completed')
                    ->with([
                        'projectName' => $this->project->name,
                    ]);
    }
}
