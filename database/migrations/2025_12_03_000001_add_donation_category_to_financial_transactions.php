<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table
        if (DB::getDriverName() === 'sqlite') {
            // Get all existing data
            $transactions = DB::table('financial_transactions')->get();
            
            // Drop the old table
            Schema::dropIfExists('financial_transactions');
            
            // Create the new table with updated category enum
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
                $table->enum('type', ['income', 'expense'])->default('expense');
                $table->enum('category', ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'donation', 'other']);
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('account')->nullable();
                $table->date('transaction_date');
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->string('invoice_number')->nullable();
                $table->timestamps();
                
                $table->index('project_id');
                $table->index('type');
                $table->index('category');
                $table->index('status');
                $table->index('transaction_date');
            });
            
            // Re-insert the data
            foreach ($transactions as $transaction) {
                DB::table('financial_transactions')->insert((array) $transaction);
            }
        } else {
            // For MySQL and other databases
            DB::statement("ALTER TABLE financial_transactions MODIFY category ENUM('salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'donation', 'other')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Get all existing data
            $transactions = DB::table('financial_transactions')->get();
            
            // Drop the old table
            Schema::dropIfExists('financial_transactions');
            
            // Create the table with the original category enum
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
                $table->enum('type', ['income', 'expense'])->default('expense');
                $table->enum('category', ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other']);
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('account')->nullable();
                $table->date('transaction_date');
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->string('invoice_number')->nullable();
                $table->timestamps();
                
                $table->index('project_id');
                $table->index('type');
                $table->index('category');
                $table->index('status');
                $table->index('transaction_date');
            });
            
            // Re-insert the data, filtering out donation records
            foreach ($transactions as $transaction) {
                if ($transaction->category !== 'donation') {
                    DB::table('financial_transactions')->insert((array) $transaction);
                }
            }
        } else {
            // For MySQL and other databases
            DB::statement("ALTER TABLE financial_transactions MODIFY category ENUM('salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other')");
        }
    }
};
