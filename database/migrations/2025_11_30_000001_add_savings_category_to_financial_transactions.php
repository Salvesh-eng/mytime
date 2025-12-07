<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table with the updated enum
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Get all existing data
            $transactions = DB::table('financial_transactions')->get();
            
            // Drop the old table
            Schema::dropIfExists('financial_transactions');
            
            // Create the new table with updated category enum
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
                $table->enum('type', ['income', 'expense'])->default('expense');
                $table->enum('category', ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other']);
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('account', ['cash', 'anz_expense', 'anz_savings'])->default('cash');
                $table->date('transaction_date');
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                
                $table->index('project_id');
                $table->index('type');
                $table->index('category');
                $table->index('status');
                $table->index('transaction_date');
            });
            
            // Reinsert all the data
            foreach ($transactions as $transaction) {
                DB::table('financial_transactions')->insert((array) $transaction);
            }
        } else {
            // For other databases, use raw SQL
            DB::statement("ALTER TABLE financial_transactions MODIFY category ENUM('salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Get all existing data
            $transactions = DB::table('financial_transactions')->get();
            
            // Drop the table
            Schema::dropIfExists('financial_transactions');
            
            // Recreate with original enum
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
                $table->enum('type', ['income', 'expense'])->default('expense');
                $table->enum('category', ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'other']);
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('account', ['cash', 'anz_expense', 'anz_savings'])->default('cash');
                $table->date('transaction_date');
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                
                $table->index('project_id');
                $table->index('type');
                $table->index('category');
                $table->index('status');
                $table->index('transaction_date');
            });
            
            // Reinsert all data except those with 'savings' category
            foreach ($transactions as $transaction) {
                if ($transaction->category !== 'savings') {
                    DB::table('financial_transactions')->insert((array) $transaction);
                }
            }
        } else {
            DB::statement("ALTER TABLE financial_transactions MODIFY category ENUM('salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'other')");
        }
    }
};
