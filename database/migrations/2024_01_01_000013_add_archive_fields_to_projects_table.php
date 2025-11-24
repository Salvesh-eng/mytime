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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('progress');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->decimal('estimated_hours', 8, 2)->default(0)->after('archived_at');
            $table->decimal('actual_hours', 8, 2)->default(0)->after('estimated_hours');
            $table->string('slug')->unique()->nullable()->after('actual_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at', 'estimated_hours', 'actual_hours', 'slug']);
        });
    }
};
