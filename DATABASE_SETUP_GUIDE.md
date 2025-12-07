# Database Setup Guide for Income & Expense Management

## Overview
This guide explains the new database tables created to store all income and expense data persistently.

## New Database Tables

### 1. **income_sources** Table
Stores all income sources defined by users.

**Columns:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `source_name` - Name of the income source (e.g., "Salary", "Freelance Work")
- `category` - Category: employment, business, government, passive, other
- `type` - Type: recurring or variable
- `description` - Optional description
- `is_active` - Boolean flag for active/inactive sources
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Purpose:** Tracks all income sources defined by the user. Data persists across page refreshes.

---

### 2. **monthly_income_budgets** Table
Stores monthly income budgets and actual income received.

**Columns:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `month` - Month number (0-11, where 0=January, 11=December)
- `year` - Year (e.g., 2025)
- `budgeted_amount` - Expected income for the month
- `actual_amount` - Actual income received for the month
- `notes` - Optional notes
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Unique Constraint:** (user_id, month, year) - One record per user per month per year

**Purpose:** Tracks monthly income budgets and actual income. Allows comparison between expected and actual income.

---

### 3. **monthly_expense_budgets** Table
Stores monthly expense budgets.

**Columns:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `month` - Month number (0-11)
- `year` - Year (e.g., 2025)
- `budgeted_amount` - Budgeted expenses for the month
- `notes` - Optional notes
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Unique Constraint:** (user_id, month, year) - One record per user per month per year

**Purpose:** Stores the monthly expense budget. Actual expenses are tracked in the expense_tracking table.

---

### 4. **expense_tracking** Table
Stores individual expense transactions.

**Columns:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `expense_name` - Name of the expense
- `category` - Category: housing, food, transportation, utilities, entertainment, healthcare, education, shopping, subscriptions, other
- `type` - Type: fixed or variable
- `amount` - Expense amount
- `expense_date` - Date of the expense
- `notes` - Optional notes
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Indexes:**
- (user_id, expense_date) - For querying expenses by date
- (user_id, category) - For querying expenses by category

**Purpose:** Tracks all individual expenses. Allows detailed expense analysis and reporting.

---

## Models Created

### 1. **IncomeSource** Model
Location: `app/Models/IncomeSource.php`

**Relationships:**
- `user()` - Belongs to User
- `monthlyBudgets()` - Has many MonthlyIncomeBudget

---

### 2. **MonthlyIncomeBudget** Model
Location: `app/Models/MonthlyIncomeBudget.php`

**Relationships:**
- `user()` - Belongs to User

**Accessors:**
- `difference` - Actual amount - Budgeted amount
- `variance_percentage` - Percentage difference from budget

---

### 3. **MonthlyExpenseBudget** Model
Location: `app/Models/MonthlyExpenseBudget.php`

**Relationships:**
- `user()` - Belongs to User
- `expenses()` - Has many ExpenseTracking (filtered by month/year)

**Accessors:**
- `total_expenses` - Sum of all expenses for the month
- `remaining_budget` - Budgeted amount - Total expenses

---

### 4. **ExpenseTracking** Model
Location: `app/Models/ExpenseTracking.php`

**Relationships:**
- `user()` - Belongs to User

---

## API Endpoints

### Income Management
- `POST /admin/api/financial/income-source` - Save income source
- `POST /admin/api/financial/income-budget` - Save monthly income budget
- `POST /admin/api/financial/income-actual` - Save actual monthly income
- `GET /admin/api/financial/income-sources` - Get all income sources

### Expense Management
- `POST /admin/api/financial/expense` - Save individual expense
- `POST /admin/api/financial/expense-budget` - Save monthly expense budget
- `GET /admin/api/financial/expenses` - Get all expenses

---

## Running Migrations

To create these tables in your database, run:

```bash
php artisan migrate
```

This will execute all pending migrations, including the new ones:
- `2025_01_20_000001_create_income_sources_table.php`
- `2025_01_20_000002_create_monthly_income_budgets_table.php`
- `2025_01_20_000003_create_monthly_expense_budgets_table.php`
- `2025_01_20_000004_create_expense_tracking_table.php`

---

## Data Persistence

### Before (Without Database)
- ❌ Data stored only in browser memory
- ❌ Data lost on page refresh
- ❌ No historical tracking
- ❌ No reporting capabilities

### After (With Database)
- ✅ Data stored permanently in database
- ✅ Data persists across page refreshes
- ✅ Full historical tracking
- ✅ Advanced reporting and analytics
- ✅ Multi-user support
- ✅ Data backup and recovery

---

## Usage Example

### Saving Income Source
```php
IncomeSource::create([
    'user_id' => Auth::id(),
    'source_name' => 'Salary',
    'category' => 'employment',
    'type' => 'recurring',
    'description' => 'Monthly salary from employer',
    'is_active' => true,
]);
```

### Saving Monthly Income Budget
```php
MonthlyIncomeBudget::create([
    'user_id' => Auth::id(),
    'month' => 0, // January
    'year' => 2025,
    'budgeted_amount' => 5000,
    'actual_amount' => 0,
]);
```

### Saving Expense
```php
ExpenseTracking::create([
    'user_id' => Auth::id(),
    'expense_name' => 'Grocery Shopping',
    'category' => 'food',
    'type' => 'variable',
    'amount' => 150.50,
    'expense_date' => now(),
    'notes' => 'Weekly groceries',
]);
```

---

## Next Steps

1. Run migrations: `php artisan migrate`
2. Update income.blade.php to use API endpoints
3. Update expense.blade.php to use API endpoints
4. Test data persistence across page refreshes
5. Create reports using the stored data

