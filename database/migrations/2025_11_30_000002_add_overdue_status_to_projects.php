<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table with updated enum
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Get all existing projects
            $projects = DB::table('projects')->get()->toArray();
            
            // Drop the old table
            Schema::dropIfExists('projects');
            
            // Recreate the table with the new status enum including 'overdue'
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('status', ['planning', 'in-progress', 'completed', 'on-hold', 'cancelled', 'overdue'])->default('planning');
                $table->date('start_date')->nullable();
                $table->date('due_date')->nullable();
                $table->integer('progress')->default(0);
                $table->boolean('is_archived')->default(false);
                $table->timestamp('archived_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->decimal('estimated_hours', 10, 2)->default(0);
                $table->decimal('actual_hours', 10, 2)->default(0);
                $table->string('slug')->unique();
                $table->timestamps();
            });
            
            // Reinsert all the data
            foreach ($projects as $project) {
                // Ensure slug exists for each project
                $projectArray = (array) $project;
                if (empty($projectArray['slug'])) {
                    $baseSlug = Str::slug($projectArray['name'] ?? 'project');
                    $slug = $baseSlug;
                    $counter = 1;
                    
                    while (DB::table('projects')->where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    $projectArray['slug'] = $slug;
                }
                
                DB::table('projects')->insert($projectArray);
            }
        } else {
            // For other databases like MySQL, use ALTER TABLE
            DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('planning', 'in-progress', 'completed', 'on-hold', 'cancelled', 'overdue') DEFAULT 'planning'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Get all existing projects
            $projects = DB::table('projects')->get()->toArray();
            
            // Drop the table
            Schema::dropIfExists('projects');
            
            // Recreate with original enum
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('status', ['planning', 'in-progress', 'completed', 'on-hold', 'cancelled'])->default('planning');
                $table->date('start_date')->nullable();
                $table->date('due_date')->nullable();
                $table->integer('progress')->default(0);
                $table->boolean('is_archived')->default(false);
                $table->timestamp('archived_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->decimal('estimated_hours', 10, 2)->default(0);
                $table->decimal('actual_hours', 10, 2)->default(0);
                $table->string('slug')->unique();
                $table->timestamps();
            });
            
            // Reinsert all the data excluding overdue projects
            foreach ($projects as $project) {
                $projectArray = (array) $project;
                if ($projectArray['status'] !== 'overdue') {
                    DB::table('projects')->insert($projectArray);
                }
            }
        } else {
            DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('planning', 'in-progress', 'completed', 'on-hold', 'cancelled') DEFAULT 'planning'");
        }
    }
};

