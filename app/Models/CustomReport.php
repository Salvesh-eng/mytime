<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomReport extends Model
{
    use HasFactory;

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

    protected $casts = [
        'metrics' => 'array',
        'filters' => 'array',
        'grouping' => 'array',
        'branding' => 'array',
        'is_template' => 'boolean',
    ];

    /**
     * Get the user who created the report.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the exports of this report.
     */
    public function exports()
    {
        return $this->hasMany(ReportExport::class);
    }

    /**
     * Get available metrics for reports.
     */
    public static function getAvailableMetrics()
    {
        return [
            'hours_by_user' => 'Hours by User',
            'hours_by_project' => 'Hours by Project',
            'hours_by_date' => 'Hours by Date',
            'utilization_rate' => 'Utilization Rate',
            'project_profitability' => 'Project Profitability',
            'time_variance' => 'Time Variance (Est vs Actual)',
            'billable_vs_non_billable' => 'Billable vs Non-Billable Hours',
            'team_capacity' => 'Team Capacity Planning',
            'project_budget_status' => 'Project Budget Status',
            'employee_productivity' => 'Employee Productivity',
        ];
    }

    /**
     * Get available filters.
     */
    public static function getAvailableFilters()
    {
        return [
            'date_range' => 'Date Range',
            'user' => 'User/Employee',
            'project' => 'Project',
            'status' => 'Status',
            'department' => 'Department',
            'billable' => 'Billable Status',
        ];
    }

    /**
     * Get available grouping options.
     */
    public static function getAvailableGrouping()
    {
        return [
            'by_user' => 'By User',
            'by_project' => 'By Project',
            'by_date' => 'By Date',
            'by_department' => 'By Department',
            'by_status' => 'By Status',
            'by_week' => 'By Week',
            'by_month' => 'By Month',
        ];
    }
}
