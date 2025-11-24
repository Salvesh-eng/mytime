<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index(Request $request)
    {
        $dateRange = $request->query('range', 'month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Determine date range
        [$start, $end] = $this->getDateRange($dateRange, $startDate, $endDate);
        [$prevStart, $prevEnd] = $this->getPreviousDateRange($start, $end);

        // Get current period data
        $currentData = $this->getAnalyticsData($start, $end);
        $previousData = $this->getAnalyticsData($prevStart, $prevEnd);

        // Calculate comparisons
        $comparisons = $this->calculateComparisons($currentData, $previousData);

        // Get detailed data for charts
        $dailyData = $this->getDailyData($start, $end);
        $projectStatus = $this->getProjectStatusData();
        $teamUtilization = $this->getTeamUtilizationData($start, $end);
        $hoursByProject = $this->getHoursByProject($start, $end);

        return view('admin.analytics.index', [
            'currentData' => $currentData,
            'comparisons' => $comparisons,
            'dateRange' => $dateRange,
            'startDate' => $start,
            'endDate' => $end,
            'dailyData' => $dailyData,
            'projectStatus' => $projectStatus,
            'teamUtilization' => $teamUtilization,
            'hoursByProject' => $hoursByProject,
        ]);
    }

    /**
     * Get analytics data for a date range.
     */
    private function getAnalyticsData(Carbon $start, Carbon $end)
    {
        $timeEntries = TimeEntry::whereBetween('entry_date', [$start, $end])
            ->with('user', 'project')
            ->get();

        $totalHours = $timeEntries->sum('duration_minutes') / 60;
        $billableHours = $timeEntries->where('is_billable', true)->sum('duration_minutes') / 60;
        $billablePercentage = $totalHours > 0 ? ($billableHours / $totalHours) * 100 : 0;

        $activeProjects = Project::where('is_archived', false)
            ->whereBetween('start_date', [$start->copy()->subMonths(3), $end])
            ->count();

        $daysWorked = $timeEntries->pluck('entry_date')->unique()->count();
        $averageHoursPerDay = $daysWorked > 0 ? $totalHours / $daysWorked : 0;

        // Calculate revenue (assuming $50/hour billable rate - adjust as needed)
        $revenue = $billableHours * 50;

        // Team utilization
        $totalEmployees = User::where('role', 'user')->count();
        $utilizationRate = ($totalEmployees > 0 && $daysWorked > 0) ? ($totalHours / ($totalEmployees * $daysWorked * 8)) * 100 : 0;

        return [
            'total_hours' => round($totalHours, 1),
            'billable_hours' => round($billableHours, 1),
            'billable_percentage' => round($billablePercentage, 1),
            'active_projects' => $activeProjects,
            'average_hours_per_day' => round($averageHoursPerDay, 1),
            'revenue' => round($revenue, 2),
            'utilization_rate' => round(min(100, $utilizationRate), 1),
            'days_worked' => $daysWorked,
            'total_entries' => $timeEntries->count(),
        ];
    }

    /**
     * Get project status breakdown.
     */
    private function getProjectStatusData()
    {
        $projects = Project::where('is_archived', false)->get();

        $onTrack = $projects->filter(fn($p) => $p->progress <= 100 && $p->progress > 50)->count();
        $atRisk = $projects->filter(fn($p) => $p->progress <= 50 && $p->progress > 0)->count();
        $ahead = $projects->filter(fn($p) => $p->progress > 100)->count();

        return [
            'total' => $projects->count(),
            'on_track' => $onTrack,
            'at_risk' => $atRisk,
            'ahead' => $ahead,
        ];
    }

    /**
     * Get team utilization breakdown.
     */
    private function getTeamUtilizationData($start, $end)
    {
        $employees = User::where('role', 'user')
            ->with('timeEntries')
            ->get()
            ->map(function ($user) use ($start, $end) {
                $hours = $user->timeEntries()
                    ->whereBetween('entry_date', [$start, $end])
                    ->sum('duration_minutes') / 60;

                return [
                    'name' => $user->name,
                    'hours' => round($hours, 1),
                ];
            })
            ->sortByDesc('hours');

        return $employees;
    }

    /**
     * Get daily data for sparklines.
     */
    private function getDailyData($start, $end)
    {
        $days = [];
        $values = [];

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $days[] = $date->format('M d');

            $dayHours = TimeEntry::whereDate('entry_date', $date)
                ->sum('duration_minutes') / 60;

            $values[] = round($dayHours, 1);
        }

        return [
            'labels' => $days,
            'values' => $values,
        ];
    }

    /**
     * Get hours by project.
     */
    private function getHoursByProject($start, $end)
    {
        return TimeEntry::whereBetween('entry_date', [$start, $end])
            ->with('project')
            ->get()
            ->groupBy('project_id')
            ->map(function ($entries, $projectId) {
                $project = $entries->first()->project;
                return [
                    'project' => $project->name ?? 'Unassigned',
                    'hours' => round($entries->sum('duration_minutes') / 60, 1),
                    'entries' => $entries->count(),
                ];
            })
            ->sortByDesc('hours')
            ->take(10);
    }

    /**
     * Get date range based on filter.
     */
    private function getDateRange($range, $customStart = null, $customEnd = null)
    {
        $today = Carbon::today();

        return match ($range) {
            'today' => [$today, $today],
            'yesterday' => [$today->copy()->subDay(), $today->copy()->subDay()],
            '7days' => [$today->copy()->subDays(6), $today],
            '30days' => [$today->copy()->subDays(29), $today],
            'month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            'last_month' => [$today->copy()->subMonth()->startOfMonth(), $today->copy()->subMonth()->endOfMonth()],
            'custom' => [Carbon::parse($customStart), Carbon::parse($customEnd)],
            default => [$today->copy()->startOfMonth(), $today],
        };
    }

    /**
     * Get previous period for comparison.
     */
    private function getPreviousDateRange(Carbon $start, Carbon $end)
    {
        $interval = $start->diffInDays($end);
        $prevEnd = $start->copy()->subDay();
        $prevStart = $prevEnd->copy()->subDays($interval);

        return [$prevStart, $prevEnd];
    }

    /**
     * Calculate comparisons between periods.
     */
    private function calculateComparisons($current, $previous)
    {
        return [
            'hours_change' => $previous['total_hours'] > 0
                ? round((($current['total_hours'] - $previous['total_hours']) / $previous['total_hours']) * 100, 1)
                : 0,
            'billable_change' => $previous['billable_hours'] > 0
                ? round((($current['billable_hours'] - $previous['billable_hours']) / $previous['billable_hours']) * 100, 1)
                : 0,
            'utilization_change' => round($current['utilization_rate'] - $previous['utilization_rate'], 1),
            'revenue_change' => $previous['revenue'] > 0
                ? round((($current['revenue'] - $previous['revenue']) / $previous['revenue']) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get analytics data via API (for auto-refresh).
     */
    public function getMetrics(Request $request)
    {
        $dateRange = $request->query('range', 'month');
        [$start, $end] = $this->getDateRange($dateRange);

        $data = $this->getAnalyticsData($start, $end);
        $dailyData = $this->getDailyData($start, $end);

        return response()->json([
            'metrics' => $data,
            'daily_data' => $dailyData,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'csv');
        $dateRange = $request->query('range', 'month');
        [$start, $end] = $this->getDateRange($dateRange);

        $data = $this->getAnalyticsData($start, $end);
        $entries = TimeEntry::whereBetween('entry_date', [$start, $end])
            ->with('user', 'project')
            ->get();

        return match ($format) {
            'csv' => $this->exportCsv($entries, $data),
            'xlsx' => $this->exportExcel($entries, $data),
            'pdf' => $this->exportPdf($entries, $data),
            default => response()->json(['error' => 'Invalid format'], 400),
        };
    }

    /**
     * Export as CSV.
     */
    private function exportCsv($entries, $summary)
    {
        $filename = 'analytics_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($entries, $summary) {
            $file = fopen('php://output', 'w');

            // Summary
            fputcsv($file, ['Analytics Summary']);
            fputcsv($file, ['Total Hours', $summary['total_hours']]);
            fputcsv($file, ['Billable Hours', $summary['billable_hours']]);
            fputcsv($file, ['Average Hours/Day', $summary['average_hours_per_day']]);
            fputcsv($file, ['Revenue', '$' . number_format($summary['revenue'], 2)]);

            fputcsv($file, []);
            fputcsv($file, ['Time Entries']);
            fputcsv($file, ['Date', 'User', 'Project', 'Hours', 'Type', 'Description']);

            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->entry_date->format('Y-m-d'),
                    $entry->user->name,
                    $entry->project->name ?? 'N/A',
                    round($entry->duration_minutes / 60, 2),
                    $entry->is_billable ? 'Billable' : 'Internal',
                    $entry->description ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Export as Excel (requires maatwebsite/excel).
     */
    private function exportExcel($entries, $summary)
    {
        return response()->json([
            'message' => 'Excel export requires Laravel Excel package installation',
            'hint' => 'composer require maatwebsite/excel',
        ], 501);
    }

    /**
     * Export as PDF (requires TCPDF or similar).
     */
    private function exportPdf($entries, $summary)
    {
        return response()->json([
            'message' => 'PDF export requires PDF generation package installation',
            'hint' => 'composer require barryvdh/laravel-dompdf',
        ], 501);
    }
}
