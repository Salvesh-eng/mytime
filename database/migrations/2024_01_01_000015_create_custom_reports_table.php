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
        Schema::create('custom_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('metrics'); // Selected metrics
            $table->json('filters')->nullable(); // Applied filters
            $table->json('grouping')->nullable(); // Grouping options
            $table->json('branding')->nullable(); // Custom branding options
            $table->string('created_by_user_id')->nullable();
            $table->boolean('is_template')->default(false);
            $table->timestamps();
        });

        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_report_id')->nullable();
            $table->string('format'); // pdf, excel, csv
            $table->string('filename');
            $table->string('file_path');
            $table->unsignedBigInteger('exported_by_user_id');
            $table->timestamps();
            
            $table->foreign('custom_report_id')->references('id')->on('custom_reports')->onDelete('cascade');
            $table->foreign('exported_by_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_exports');
        Schema::dropIfExists('custom_reports');
    }
};
