<?php
/**
 * MIGRATION EXECUTION GUIDE
 * ========================
 * 
 * This file contains the order and details of all migrations needed for
 * project management enhancements.
 * 
 * Execute all migrations with:
 * php artisan migrate
 * 
 * OR run them individually in order:
 */

/**
 * Migration 1: Create Project Tags Table
 * File: database/migrations/2024_01_01_000008_create_project_tags_table.php
 * 
 * Creates table to store project categories (Development, Marketing, etc.)
 * Allows multiple tags per project with color coding
 */

/**
 * Migration 2: Create Project Budgets Table
 * File: database/migrations/2024_01_01_000009_create_project_budgets_table.php
 * 
 * Stores budget information:
 * - allocated_budget: Total approved budget
 * - spent_amount: Amount spent so far
 * - currency: Currency code (USD, EUR, etc.)
 * - notes: Additional budget notes
 */

/**
 * Migration 3: Create Project Milestones Table
 * File: database/migrations/2024_01_01_000010_create_project_milestones_table.php
 * 
 * Tracks project phases/milestones:
 * - title: Milestone name
 * - target_date: Deadline
 * - status: pending, in-progress, completed, overdue
 * - completion_percentage: 0-100%
 * - deliverables: JSON array of deliverables
 */

/**
 * Migration 4: Create Project Clients Table
 * File: database/migrations/2024_01_01_000011_create_project_clients_table.php
 * 
 * Stores client information:
 * - Contact details (person, email, phone)
 * - Physical address
 * - Billing information (separate address, tax ID)
 * - Billing notes for invoicing
 */

/**
 * Migration 5: Create Project Templates Table
 * File: database/migrations/2024_01_01_000012_create_project_templates_table.php
 * 
 * Enables project template creation:
 * - name: Template name
 * - team_members: JSON array of team member IDs
 * - tasks: JSON array of predefined tasks
 * - estimated_hours: JSON with hour estimates
 * - usage_count: Number of times template was used
 * - is_active: Toggle template availability
 */

/**
 * Migration 6: Add Archive Fields to Projects Table
 * File: database/migrations/2024_01_01_000013_add_archive_fields_to_projects_table.php
 * 
 * Adds archival support to existing projects:
 * - is_archived: Boolean flag for archived status
 * - archived_at: Timestamp of when archived
 * - estimated_hours: For progress calculation
 * - actual_hours: For progress tracking
 * - slug: URL-friendly project identifier
 */

/**
 * EXECUTION STEPS
 * ===============
 */

// Step 1: Backup your database (IMPORTANT!)
// mysqldump -u username -p database_name > backup.sql

// Step 2: Run all migrations
// php artisan migrate

// Step 3: Verify migrations
// php artisan migrate:status

// Step 4: Check database tables
// Show tables to verify:
// - project_tags
// - project_budgets
// - project_milestones
// - project_clients
// - project_templates
// - projects (modified with new columns)

/**
 * ROLLBACK INSTRUCTIONS
 * =====================
 * 
 * If you need to rollback (undo):
 * php artisan migrate:rollback
 * 
 * Rollback specific migration:
 * php artisan migrate:rollback --step=1
 */

/**
 * DATABASE SCHEMA OVERVIEW
 * ========================
 */

// project_tags
// ├── id (primary)
// ├── project_id (foreign)
// ├── category (enum)
// ├── color (string)
// ├── description (text, nullable)
// └── timestamps

// project_budgets
// ├── id (primary)
// ├── project_id (foreign)
// ├── allocated_budget (decimal)
// ├── spent_amount (decimal)
// ├── currency (string)
// ├── notes (text, nullable)
// └── timestamps

// project_milestones
// ├── id (primary)
// ├── project_id (foreign)
// ├── title (string)
// ├── description (text, nullable)
// ├── target_date (date)
// ├── status (enum)
// ├── completion_percentage (integer)
// ├── deliverables (array, nullable)
// └── timestamps

// project_clients
// ├── id (primary)
// ├── project_id (foreign)
// ├── client_name (string)
// ├── contact_person (string, nullable)
// ├── email (email, nullable)
// ├── phone (string, nullable)
// ├── company_name (string, nullable)
// ├── address (text, nullable)
// ├── city (string, nullable)
// ├── state (string, nullable)
// ├── postal_code (string, nullable)
// ├── country (string, nullable)
// ├── billing_email (email, nullable)
// ├── billing_address (text, nullable)
// ├── tax_id (string, nullable)
// ├── billing_notes (text, nullable)
// └── timestamps

// project_templates
// ├── id (primary)
// ├── name (string)
// ├── description (text, nullable)
// ├── created_by (foreign to users)
// ├── tasks (json, nullable)
// ├── team_members (json, nullable)
// ├── estimated_hours (json, nullable)
// ├── is_active (boolean)
// ├── usage_count (integer)
// └── timestamps

// projects (MODIFIED - NEW COLUMNS)
// ├── ... existing columns ...
// ├── is_archived (boolean, default: false)
// ├── archived_at (timestamp, nullable)
// ├── estimated_hours (decimal, nullable)
// ├── actual_hours (decimal, nullable)
// └── slug (string, unique, nullable)

/**
 * VERIFICATION QUERIES
 * ====================
 * 
 * Run these after migration to verify everything is set up:
 */

// Check all new tables exist:
// SHOW TABLES LIKE 'project_%';

// Verify projects table structure:
// DESCRIBE projects;
// Should show new columns: is_archived, archived_at, estimated_hours, actual_hours, slug

// Check relationships:
// SELECT * FROM project_tags LIMIT 1;
// SELECT * FROM project_budgets LIMIT 1;
// SELECT * FROM project_milestones LIMIT 1;
// SELECT * FROM project_clients LIMIT 1;
// SELECT * FROM project_templates LIMIT 1;

/**
 * POST-MIGRATION SETUP
 * ====================
 * 
 * 1. Clear application cache:
 *    php artisan cache:clear
 *    php artisan config:clear
 * 
 * 2. Restart development server if running
 * 
 * 3. Test in browser:
 *    - Visit /admin/projects
 *    - Create new project
 *    - Add tags, budget, milestone, client
 *    - Save as template
 *    - Archive project
 * 
 * 4. Monitor logs:
 *    tail -f storage/logs/laravel.log
 */

/**
 * TROUBLESHOOTING
 * ===============
 * 
 * Issue: Foreign key constraint error
 * Solution: Check that projects table exists and projects with IDs exist
 * 
 * Issue: Migration fails on column already exists
 * Solution: Run: php artisan migrate:reset (caution: deletes all data)
 * 
 * Issue: UUID vs integer ID mismatch
 * Solution: Verify project_id foreign key type matches projects table id type
 * 
 * Issue: Enum values not recognized
 * Solution: Use SHOW COLUMNS FROM table_name to verify enum values
 */

?>
