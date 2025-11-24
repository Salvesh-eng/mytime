<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action_type'); // 'start_timer', 'submit_timesheet', 'approve_entry', 'reject_entry', 'create_project', etc.
            $table->text('description');
            $table->string('model_type')->nullable(); // 'TimeEntry', 'Project', 'User', etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
