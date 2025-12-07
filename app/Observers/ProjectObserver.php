<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Carbon;

class ProjectObserver
{
    /**
     * Handle the Project "retrieved" event.
     * This is called whenever a project is retrieved from the database.
     */
    public function retrieved(Project $project)
    {
        // Automatically update status to overdue if due date has passed
        $this->updateOverdueStatus($project);
    }

    /**
     * Handle the Project "creating" event.
     */
    public function creating(Project $project)
    {
        //
    }

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project)
    {
        //
    }

    /**
     * Handle the Project "updating" event.
     */
    public function updating(Project $project)
    {
        // Update status to overdue if conditions are met
        $this->updateOverdueStatus($project);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project)
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project)
    {
        //
    }

    /**
     * Update project status to overdue if conditions are met
     */
    private function updateOverdueStatus(Project $project)
    {
        // Check if project should be marked as overdue
        if ($project->due_date && 
            $project->due_date < Carbon::now()->toDateString() && 
            $project->status !== 'completed' && 
            $project->status !== 'overdue') {
            
            // Update silently without triggering observers again
            Project::withoutEvents(function () use ($project) {
                $project->forceFill(['status' => 'overdue'])->save();
            });
        }
    }
}
