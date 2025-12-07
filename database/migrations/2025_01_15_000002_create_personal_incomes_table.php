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
        Schema::create('personal_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained('personal_accounts')->onDelete('set null');
            $table->date('income_date');
            $table->string('invoice_number')->nullable();
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('FJD');
            $table->text('description')->nullable();
            $table->string('status')->default('completed'); // pending, completed
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('income_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_incomes');
    }
};
