# Reporting & Analytics - Quick Start Guide

## 🚀 Getting Started

### Step 1: Run Migrations
```bash
php artisan migrate
```

This creates the necessary database tables for custom reports and exports.

### Step 2: Access Reports
Navigate to: **Admin Dashboard → Reports**

---

## 📊 Five Key Features

### 1. Exportable Reports (PDF, Excel, CSV)
**What it does**: Export any report in multiple formats with custom branding

**How to use**:
1. Go to Reports dashboard
2. Click "Build Custom Report"
3. Select metrics and configure options
4. View the report
5. Click "Export PDF" or "Export Excel"

**Customization**:
- Primary color
- Header color
- Company name

---

### 2. Custom Report Builder
**What it does**: Create reports by selecting metrics, filters, and grouping

**How to use**:
1. Click "+ Build Custom Report"
2. Enter report name and description
3. Select metrics (check multiple):
   - Hours by User
   - Hours by Project
   - Utilization Rate
   - Project Profitability
   - Time Variance
   - And more...
4. Apply filters (optional):
   - Date range
   - Specific user
   - Specific project
5. Choose grouping (optional):
   - By User
   - By Project
   - By Date
   - By Month
6. Customize branding
7. Click "Create Report"

**Save as Template**: Check the box to reuse this report configuration

---

### 3. Utilization Reports
**What it does**: Track how efficiently employees are using their time

**Key Metrics**:
- **Billable Hours**: Hours logged by employee
- **Total Hours**: Total available hours
- **Utilization Rate**: Percentage of time utilized
- **Capacity Available**: Remaining capacity

**How to view**:
1. Go to Reports dashboard
2. Click "Utilization" tab
3. See all employees' utilization rates
4. Green progress bars show utilization percentage

**What to look for**:
- High utilization (80-100%) = Good resource usage
- Low utilization (<50%) = Potential capacity issues
- Capacity available = Room for more projects

---

### 4. Project Profitability Analysis
**What it does**: Compare project costs vs revenue to determine profitability

**Key Metrics**:
- **Total Hours**: Hours spent on project
- **Estimated Cost**: Hours × $50/hour
- **Actual Revenue**: Project budget
- **Profitability**: Revenue - Cost
- **Margin %**: Profit margin percentage

**How to view**:
1. Go to Reports dashboard
2. Click "Profitability" tab
3. See profit/loss for each project

**What to look for**:
- Green numbers = Profitable projects
- Red numbers = Loss-making projects
- High margin % = Efficient projects
- Negative margin = Over budget

---

### 5. Time Comparison Reports
**What it does**: Compare estimated vs actual time to identify variances

**Key Metrics**:
- **Estimated Hours**: Original project estimate
- **Actual Hours**: Hours actually spent
- **Variance**: Difference (Actual - Estimated)
- **Variance %**: Percentage difference
- **Status**: Over/Under/On-Track

**Status Meanings**:
- 🔴 **Over**: Actual > Estimated by >10%
- 🟢 **Under**: Actual < Estimated by >10%
- 🔵 **On-Track**: Within ±10% of estimate

**How to view**:
1. Go to Reports dashboard
2. Click "Time Variance" tab
3. See variance for each project

**What to look for**:
- Projects marked "Over" = Need investigation
- Projects marked "Under" = Good estimation
- Patterns = Improve future estimates

---

## 🎯 Common Tasks

### Task 1: Export a Report as PDF
1. Go to Reports → Custom Reports
2. Click "View Report" on desired report
3. Click "📄 Export PDF"
4. File downloads automatically

### Task 2: Create Monthly Utilization Report
1. Click "+ Build Custom Report"
2. Name: "Monthly Utilization - [Month]"
3. Select metric: "Utilization Rate"
4. Set date range: 1st to last day of month
5. Group by: "By User"
6. Click "Create Report"
7. Export as needed

### Task 3: Identify Unprofitable Projects
1. Go to Reports → Profitability tab
2. Look for red numbers in "Profitability" column
3. Click project name to see details
4. Review hours and budget allocation

### Task 4: Find Projects Over Estimate
1. Go to Reports → Time Variance tab
2. Look for "Over" status badges
3. Review variance percentage
4. Investigate reasons for overrun

### Task 5: Save Report as Template
1. Click "+ Build Custom Report"
2. Configure report as desired
3. Check "Save as Template"
4. Click "Create Report"
5. Reuse configuration for future reports

---

## 📈 Dashboard Overview

### Main Reports Page Tabs

| Tab | Purpose | Key Info |
|-----|---------|----------|
| Hours Summary | Total hours by user/date | Daily tracking |
| Utilization | Employee efficiency | Capacity planning |
| Profitability | Project profit/loss | Financial analysis |
| Time Variance | Est vs Actual | Project accuracy |

---

## 🔧 Customization Options

### Branding Settings
When creating a report, customize:
- **Primary Color**: Main heading color (default: #2563EB)
- **Header Color**: Table header background (default: #f2f2f2)
- **Company Name**: Appears in exports

### Filter Options
- **Date Range**: From/To dates
- **User**: Specific employee
- **Project**: Specific project
- **Status**: Entry status (approved, pending, etc.)

### Grouping Options
- By User
- By Project
- By Date
- By Department
- By Status
- By Week
- By Month

---

## 📱 Viewing Reports

### On Dashboard
- See all reports at a glance
- Quick tabs for different report types
- Filter by date range
- Export directly from dashboard

### In Custom Reports List
- View all saved custom reports
- See report metadata
- Quick access to view/delete
- Pagination for many reports

### Individual Report View
- Full report details
- All metrics displayed
- Export options (PDF, Excel)
- Delete option

---

## 💾 Export Formats

### PDF Export
- Professional formatting
- Custom branding applied
- Print-friendly
- Best for: Sharing with stakeholders

### Excel Export
- Spreadsheet format
- Multiple sheets per metric
- Sortable/filterable
- Best for: Data analysis

### CSV Export
- Simple text format
- Easy to import
- Lightweight
- Best for: Data integration

---

## ⚙️ System Requirements

- Database with time entries
- Approved time entries (status = "approved")
- Active users and projects
- Date range with data

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| No data in report | Check date range, verify entries are approved |
| Export fails | Check file permissions, ensure disk space |
| Slow report generation | Reduce date range, add database indexes |
| Branding not showing | Verify hex color format (#RRGGBB) |

---

## 📚 Additional Resources

- Full documentation: `REPORTING_ANALYTICS_GUIDE.md`
- Implementation details: `IMPLEMENTATION_COMPLETE.md`
- Project setup: `SETUP.md`

---

## 🎓 Best Practices

1. **Regular Monitoring**: Check utilization weekly
2. **Variance Analysis**: Review time variance monthly
3. **Profitability Review**: Analyze project profitability quarterly
4. **Template Usage**: Create templates for recurring reports
5. **Export Archive**: Keep copies of important reports
6. **Date Ranges**: Use consistent periods for comparison
7. **Filtering**: Use filters to focus on specific areas

---

## 🚀 Next Steps

1. ✅ Run migrations
2. ✅ Access Reports dashboard
3. ✅ Create first custom report
4. ✅ Export in preferred format
5. ✅ Share with team
6. ✅ Set up recurring reports
7. ✅ Monitor key metrics

---

**Happy Reporting! 📊**
