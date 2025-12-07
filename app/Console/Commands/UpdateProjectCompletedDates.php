<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProjectCompletedDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:update-completed-dates';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Update completed_at field for completed projects without one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Update completed projects that don't have completed_at set
        $updated = Project::where('status', 'completed')
            ->whereNull('completed_at')
            ->update(['completed_at' => DB::raw('updated_at')]);

        $this->info("Updated $updated projects with completed_at dates.");

        // Also, let's create some test data with completed_at in the last 30 days
        $projectsNeedingDates = Project::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get();

        $this->info("Total completed projects: " . $projectsNeedingDates->count());
    }
}
