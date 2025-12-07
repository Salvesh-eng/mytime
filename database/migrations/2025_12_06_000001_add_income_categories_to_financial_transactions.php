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
        // For SQLite, we need to recreate the table with the new enum values
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Get all existing data
            $transactions = DB::table('financial_transactions')->get();
            
            // Drop the old table
            Schema::dropIfExists('financial_transactions');
            
            // Create the new table with updated enum
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
                $table->enum('type', ['income', 'expense'])->default('expense');
                $table->enum('category', [
                    'salary', 
                    'equipment', 
                    'software', 
                    'travel', 
                    'utilities', 
                    'marketing', 
                    'client_payment', 
                    'savings', 
                    'other',
                    'investment_return',
                    'bonus',
                    'freelance',
                    'rental',
                    'gift',
                    'donation'
                ]);
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('account', ['cash', 'anz_expense', 'anz_savings', 'cash_on_hand', 'bank_expense', 'bank_savings', 'm_paisa'])->nullable();
                $table->string('invoice_number')->nullable();
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
            
            // Re-insert the data, only including columns that exist in the new table
            foreach ($transactions as $transaction) {
                $data = (array) $transaction;
                // Only insert columns that exist in the new schema
                $allowedColumns = ['id', 'project_id', 'type', 'category', 'description', 'amount', 'currency', 'account', 'invoice_number', 'transaction_date', 'status', 'notes', 'created_by', 'approved_by', 'approved_at', 'created_at', 'updated_at'];
                $filteredData = array_intersect_key($data, array_flip($allowedColumns));
                DB::table('financial_transactions')->insert($filteredData);
            }
        } else {
            // For MySQL/PostgreSQL, use ALTER TABLE
            DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN category ENUM('salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other', 'investment_return', 'bonus', 'freelance', 'rental', 'gift', 'donation')");
            
            // Add invoice_number column if it doesn't exist
            if (!Schema::hasColumn('financial_transactions', 'invoice_number')) {
                Schema::table('financial_transactions', function (Blueprint $table) {
                    $table->string('invoice_number')->nullable();
                });
            }
            
            // Add account enum values if needed
            DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN account ENUM('cash', 'anz_expense', 'anz_savings', 'cash_on_hand', 'bank_expense', 'bank_savings', 'm_paisa')");
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
            
            // Drop the new table
            Schema::dropIfExists('financial_transactions');
            
            // Recreate the old table with original enum
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
                $table->enum('type', ['income', 'expense'])->default('expense');
                $table->enum('category', ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other']);
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
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
            
            // Re-insert the data
            foreach ($transactions as $transaction) {
                DB::table('financial_transactions')->insert((array) $transaction);
            }
        } else {
            DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN category ENUM('salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'savings', 'other')");
        }
    }
};
