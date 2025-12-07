# Reporting & Analytics Implementation Guide

This document outlines the comprehensive reporting and analytics features implemented in the MyTime application.

## Overview

The reporting system includes five major features:

1. **Exportable Reports** - Export reports in PDF, Excel, and CSV formats with custom branding
2. **Custom Report Builder** - Create custom reports with selected metrics, filters, and grouping
3. **Utilization Reports** - Track employee utilization rates and capacity planning
4. **Project Profitability Analysis** - Compare project costs vs revenue
5. **Time Comparison Reports** - Estimated vs actual time with variance analysis

---

## Feature 1: Exportable Reports (Change 41)

### Overview
All reports can be exported in multiple formats (PDF, Excel, CSV) with custom branding options.

### Implementation Details

#### Models
- `CustomReport` - Stores custom report configurations
- `ReportExport` - Tracks all report exports

#### Controller Methods
- `exportCsv()` - Export basic time report as CSV
- `exportPdf(CustomReport)` - Export custom report as PDF with branding
- `exportExcel(CustomReport)` - Export custom report as Excel

#### Features
- **PDF Export**: Generates HTML-based PDF with custom colors and branding
- **Excel Export**: Creates formatted Excel files with multiple sheets
- **CSV Export**: Simple comma-separated values for data import
- **Custom Branding**: 
  - Primary color customization
  - Header color customization
  - Company name inclusion
  - Logo support (extensible)

#### Usage
```php
// Export as PDF
GET /admin/reports/custom/{id}/export-pdf

// Export as Excel
GET /admin/reports/custom/{id}/export-excel

// Export basic report as CSV
GET /admin/reports/export-csv?date_from=2024-01-01&date_to=2024-01-31
```

#### Database Schema
```sql
-- Tracks all report exports
CREATE TABLE report_exports (
    id BIGINT PRIMARY KEY,
    custom_report_id BIGINT,
    format VARCHAR (pdf, excel, csv),
    filename VARCHAR,
    file_path VARCHAR,
    exported_by_user_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## Feature 2: Custom Report Builder (Change 42)

### Overview
Users can build custom reports by selecting metrics, filters, and grouping options.

### Implementation Details

#### Available Metrics
- `hours_by_user` - Total hours logged by each user
- `hours_by_project` - Total hours per project
- `hours_by_date` - Daily hour totals
- `utilization_rate` - Employee utilization percentages
- `project_profitability` - Project profit analysis
- `time_variance` - Estimated vs actual comparison
- `billable_vs_non_billable` - Billable hours breakdown
- `team_capacity` - Team capacity planning
- `project_budget_status` - Budget utilization
- `employee_productivity` - Productivity metrics

#### Available Filters
- `date_range` - Filter by date range
- `user` - Filter by specific employee
- `project` - Filter by specific project
- `status` - Filter by entry status
- `department` - Filter by department
- `billable` - Filter by billable status

#### Available Grouping Options
- `by_user` - Group results by user
- `by_project` - Group results by project
- `by_date` - Group results by date
- `by_department` - Group results by department
- `by_status` - Group results by status
- `by_week` - Group results by week
- `by_month` - Group results by month

#### Routes
```php
// Show builder interface
GET /admin/reports/builder

// Store custom report
POST /admin/reports/custom

// View custom report
GET /admin/reports/custom/{id}

// List all custom reports
GET /admin/reports/list

// Delete custom report
DELETE /admin/reports/custom/{id}
```

#### Database Schema
```sql
CREATE TABLE custom_reports (
    id BIGINT PRIMARY KEY,
    name VARCHAR,
    description TEXT,
    metrics JSON,
    filters JSON,
    grouping JSON,
    branding JSON,
    created_by_user_id BIGINT,
    is_template BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Example Usage
```php
// Create a custom report
POST /admin/reports/custom
{
    "name": "Monthly Team Utilization",
    "description": "Track team utilization rates",
    "metrics": ["utilization_rate", "hours_by_user"],
    "filters": {
        "date_from": "2024-01-01",
        "date_to": "2024-01-31"
    },
    "grouping": ["by_user"],
    "branding": {
        "primary_color": "#2563EB",
        "header_color": "#f2f2f2",
        "company_name": "My Company"
    },
    "is_template": true
}
```

---

## Feature 3: Utilization Reports (Change 43)

### Overview
Shows employee utilization rates (billable hours / total hours) and capacity planning.

### Implementation Details

#### Metrics Calculated
- **Billable Hours**: Total hours logged by employee
- **Total Hours**: Total hours available
- **Utilization Rate**: (Billable Hours / Total Hours) × 100
- **Capacity Available**: Remaining capacity based on 40-hour work weeks

#### Controller Method
```php
private function getUtilizationData($dateFrom, $dateTo)
```

#### Data Structure
```php
[
    'user_id' => 1,
    'user_name' => 'John Doe',
    'billable_hours' => 160,
    'total_hours' => 160,
    'utilization_rate' => 100,
    'capacity_available' => 0
]
```

#### Display Features
- Progress bars showing utilization percentage
- Color-coded status indicators
- Capacity planning information
- Sortable columns

#### Usage
The utilization report is accessible via:
- Main reports dashboard (Utilization tab)
- Custom report builder (select "utilization_rate" metric)

---

## Feature 4: Project Profitability Analysis (Change 44)

### Overview
Compares project costs (team hours × rates) vs project revenue/budget.

### Implementation Details

#### Metrics Calculated
- **Total Hours**: Sum of approved time entries for project
- **Estimated Cost**: Total Hours × $50/hour (default rate)
- **Actual Revenue**: Project budget allocated amount
- **Profitability**: Actual Revenue - Estimated Cost
- **Margin Percentage**: (Profitability / Actual Revenue) × 100

#### Controller Method
```php
private function getProjectProfitability($dateFrom, $dateTo)
```

#### Data Structure
```php
[
    'project_id' => 1,
    'project_name' => 'Website Redesign',
    'total_hours' => 120,
    'estimated_cost' => 6000,
    'actual_revenue' => 8000,
    'profitability' => 2000,
    'margin_percentage' => 25
]
```

#### Display Features
- Color-coded profitability (green for positive, red for negative)
- Margin percentage calculation
- Budget vs actual comparison
- Sortable and filterable tables

#### Usage
The profitability report is accessible via:
- Main reports dashboard (Profitability tab)
- Custom report builder (select "project_profitability" metric)

---

## Feature 5: Time Comparison Reports (Change 45)

### Overview
Estimated vs actual time reports highlighting variances and reasons for discrepancies.

### Implementation Details

#### Metrics Calculated
- **Estimated Hours**: Project estimated_hours field
- **Actual Hours**: Sum of approved time entries
- **Variance**: Actual Hours - Estimated Hours
- **Variance Percentage**: (Variance / Estimated Hours) × 100
- **Status**: 
  - "over" if variance > 10%
  - "under" if variance < -10%
  - "on-track" if within ±10%

#### Controller Method
```php
private function getTimeVariance($dateFrom, $dateTo)
```

#### Data Structure
```php
[
    'project_id' => 1,
    'project_name' => 'Website Redesign',
    'estimated_hours' => 100,
    'actual_hours' => 120,
    'variance' => 20,
    'variance_percentage' => 20,
    'status' => 'over'
]
```

#### Display Features
- Status badges (Over/Under/On-Track)
- Variance percentage highlighting
- Color-coded status indicators
- Variance analysis for project management

#### Usage
The variance report is accessible via:
- Main reports dashboard (Time Variance tab)
- Custom report builder (select "time_variance" metric)

---

## Database Migrations

Run the following migration to set up the reporting tables:

```bash
php artisan migrate
```

This creates:
1. `custom_reports` table - Stores custom report configurations
2. `report_exports` table - Tracks all report exports

---

## Routes

All reporting routes are protected by admin middleware:

```php
// Main reports dashboard
GET /admin/reports

// Export basic report
GET /admin/reports/export-csv

// Custom report builder
GET /admin/reports/builder
POST /admin/reports/custom

// View custom report
GET /admin/reports/custom/{id}

// Export custom report
GET /admin/reports/custom/{id}/export-pdf
GET /admin/reports/custom/{id}/export-excel

// Manage custom reports
GET /admin/reports/list
DELETE /admin/reports/custom/{id}
```

---

## Views

### Main Reports Dashboard
**File**: `resources/views/admin/reports/index.blade.php`

Features:
- Tabbed interface for different report types
- Date range filtering
- Hours summary
- Utilization rates
- Profitability analysis
- Time variance analysis
- Export options

### Custom Report Builder
**File**: `resources/views/admin/reports/builder.blade.php`

Features:
- Report name and description
- Metric selection (checkboxes)
- Filter configuration
- Grouping options
- Custom branding settings
- Form validation

### Custom Report Display
**File**: `resources/views/admin/reports/show.blade.php`

Features:
- Report metadata display
- Dynamic metric sections
- Export buttons (PDF, Excel)
- Delete functionality
- Formatted data tables

### Custom Reports List
**File**: `resources/views/admin/reports/list.blade.php`

Features:
- Grid layout of report cards
- Report metadata
- Quick actions (View, Delete)
- Pagination support
- Empty state messaging

---

## Models

### CustomReport
```php
class CustomReport extends Model {
    protected $fillable = [
        'name',
        'description',
        'metrics',
        'filters',
        'grouping',
        'branding',
        'created_by_user_id',
        'is_template',
    ];

    public function createdBy() { ... }
    public function exports() { ... }
    public static function getAvailableMetrics() { ... }
    public static function getAvailableFilters() { ... }
    public static function getAvailableGrouping() { ... }
}
```

### ReportExport
```php
class ReportExport extends Model {
    protected $fillable = [
        'custom_report_id',
        'format',
        'filename',
        'file_path',
        'exported_by_user_id',
    ];

    public function customReport() { ... }
    public function exportedBy() { ... }
}
```

---

## Controller Methods

### ReportController

#### Public Methods
- `index(Request $request)` - Show main reports dashboard
- `builder(Request $request)` - Show custom report builder
- `storeCustomReport(Request $request)` - Store new custom report
- `showCustomReport(CustomReport $customReport)` - Display custom report
- `exportCsv(Request $request)` - Export basic report as CSV
- `exportPdf(CustomReport $customReport)` - Export custom report as PDF
- `exportExcel(CustomReport $customReport)` - Export custom report as Excel
- `listCustomReports()` - List all custom reports
- `deleteCustomReport(CustomReport $customReport)` - Delete custom report

#### Private Methods
- `generateReportData(CustomReport $customReport)` - Generate report data
- `getMetricData($metric, $filters, $grouping)` - Get specific metric data
- `getUtilizationData($dateFrom, $dateTo)` - Calculate utilization rates
- `getProjectProfitability($dateFrom, $dateTo)` - Calculate profitability
- `getTimeVariance($dateFrom, $dateTo)` - Calculate time variance
- `generatePdfHtml(...)` - Generate PDF HTML content
- `generateExcelResponse(...)` - Generate Excel response

---

## Usage Examples

### Example 1: Create a Utilization Report

1. Navigate to `/admin/reports/builder`
2. Enter report name: "Q1 Team Utilization"
3. Select metrics: "utilization_rate", "hours_by_user"
4. Set date range: Jan 1 - Mar 31, 2024
5. Select grouping: "by_user"
6. Customize branding colors
7. Click "Create Report"
8. View report and export as PDF/Excel

### Example 2: Analyze Project Profitability

1. Go to `/admin/reports`
2. Click "Profitability" tab
3. Adjust date range as needed
4. Review profit margins for each project
5. Identify over/under budget projects
6. Export data for stakeholder review

### Example 3: Track Time Variance

1. Go to `/admin/reports`
2. Click "Time Variance" tab
3. Review projects with variance > 10%
4. Identify projects over/under estimated hours
5. Use data for project planning adjustments

---

## Performance Considerations

### Query Optimization
- Queries use `whereBetween()` for date range filtering
- Indexes recommended on:
  - `time_entries.entry_date`
  - `time_entries.user_id`
  - `time_entries.project_id`
  - `time_entries.status`

### Caching Recommendations
For large datasets, consider caching:
```php
Cache::remember('utilization_' . $dateFrom . '_' . $dateTo, 3600, function() {
    return $this->getUtilizationData($dateFrom, $dateTo);
});
```

### Export Performance
- PDF generation uses HTML-based approach (lightweight)
- Excel export uses CSV streaming (memory efficient)
- Large reports may take time; consider background jobs for very large exports

---

## Future Enhancements

1. **Scheduled Reports** - Automatically generate and email reports
2. **Report Templates** - Save and reuse report configurations
3. **Advanced Filtering** - More complex filter combinations
4. **Data Visualization** - Charts and graphs for reports
5. **Report Sharing** - Share reports with team members
6. **Audit Trail** - Track who accessed/exported reports
7. **Custom Metrics** - Allow users to define custom calculations
8. **API Integration** - Export to external systems
9. **Real-time Dashboards** - Live updating report data
10. **Mobile Reports** - Optimized mobile report viewing

---

## Troubleshooting

### Issue: Reports show no data
**Solution**: Verify time entries exist with status "approved" in the date range

### Issue: Export fails
**Solution**: Check file permissions in storage directory, ensure disk space available

### Issue: Slow report generation
**Solution**: Add database indexes, consider date range limitations, use caching

### Issue: Custom branding not appearing
**Solution**: Verify color format is valid hex (#RRGGBB), check browser cache

---

## Support

For issues or questions about the reporting system, refer to:
- Main documentation: `README.md`
- Implementation guide: `IMPLEMENTATION_COMPLETE.md`
- Quick reference: `QUICK_REFERENCE.md`
