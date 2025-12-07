<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show reports page
     */
    public function index()
    {
        return view('user.reports');
    }

    /**
     * Get report statistics
     */
    public function getStatistics(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->query('start_date', now()->startOfMonth());
        $endDate = $request->query('end_date', now());
        $reportType = $request->query('report_type', '');

        $query = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate]);

        if ($reportType === 'time-entries') {
            // Already filtered
        } elseif ($reportType === 'projects') {
            $query = Project::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalHours = $query->sum('hours') ?? 0;
        $totalEntries = $query->count();
        $totalProjects = Project::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return response()->json([
            'total_hours' => $totalHours,
            'total_entries' => $totalEntries,
            'total_projects' => $totalProjects,
        ]);
    }

    /**
     * Export report in various formats
     */
    public function exportReport(Request $request)
    {
        $user = Auth::user();
        $reportType = $request->query('report_type', 'time-entries-summary');
        $format = $request->query('format', 'pdf');
        $startDate = $request->query('start_date', now()->startOfMonth());
        $endDate = $request->query('end_date', now());

        $data = $this->prepareReportData($reportType, $startDate, $endDate, $user);

        return match ($format) {
            'pdf' => $this->exportPDF($data, $reportType),
            'excel' => $this->exportExcel($data, $reportType),
            'word' => $this->exportWord($data, $reportType),
            'csv' => $this->exportCSV($data, $reportType),
            'json' => $this->exportJSON($data, $reportType),
            'zip' => $this->exportZIP($data, $reportType),
            default => response()->json(['error' => 'Invalid format'], 400),
        };
    }

    /**
     * Prepare report data based on report type
     */
    private function prepareReportData($reportType, $startDate, $endDate, $user)
    {
        return match ($reportType) {
            'time-entries-summary' => $this->getTimeEntriesSummary($startDate, $endDate, $user),
            'detailed-time-log' => $this->getDetailedTimeLog($startDate, $endDate, $user),
            'weekly-report' => $this->getWeeklyReport($startDate, $endDate, $user),
            'monthly-report' => $this->getMonthlyReport($startDate, $endDate, $user),
            'project-overview' => $this->getProjectOverview($startDate, $endDate, $user),
            'project-time-allocation' => $this->getProjectTimeAllocation($startDate, $endDate, $user),
            'project-status' => $this->getProjectStatus($startDate, $endDate, $user),
            'project-budget' => $this->getProjectBudget($startDate, $endDate, $user),
            'productivity-summary' => $this->getProductivitySummary($startDate, $endDate, $user),
            'team-performance' => $this->getTeamPerformance($startDate, $endDate, $user),
            'custom-export' => $this->getCustomExport($startDate, $endDate, $user),
            'full-backup' => $this->getFullBackup($user),
            default => [],
        };
    }

    private function getTimeEntriesSummary($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('time_entries.user_id', $user->id)
            ->whereBetween('time_entries.entry_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Time Entries Summary',
            'date_range' => "$startDate to $endDate",
            'total_hours' => $entries->sum('hours'),
            'total_entries' => $entries->count(),
            'entries' => $entries->map(function ($entry) {
                return [
                    'date' => $entry->entry_date->format('Y-m-d'),
                    'hours' => $entry->hours,
                    'status' => $entry->status,
                    'project' => $entry->project_id ? Project::find($entry->project_id)?->name : 'N/A',
                ];
            }),
        ];
    }

    private function getDetailedTimeLog($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->orderBy('entry_date', 'desc')
            ->get();

        return [
            'title' => 'Detailed Time Log',
            'date_range' => "$startDate to $endDate",
            'entries' => $entries->map(function ($entry) {
                return [
                    'date' => $entry->entry_date->format('Y-m-d'),
                    'start_time' => $entry->start_time,
                    'end_time' => $entry->end_time,
                    'hours' => $entry->hours,
                    'project' => $entry->project_id ? Project::find($entry->project_id)?->name : 'N/A',
                    'status' => $entry->status,
                    'notes' => $entry->notes ?? '',
                ];
            }),
        ];
    }

    private function getWeeklyReport($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($entry) {
                return $entry->entry_date->startOfWeek()->format('Y-m-d');
            });

        return [
            'title' => 'Weekly Report',
            'date_range' => "$startDate to $endDate",
            'weeks' => $entries->map(function ($weekEntries, $weekStart) {
                return [
                    'week_start' => $weekStart,
                    'total_hours' => $weekEntries->sum('hours'),
                    'entries_count' => $weekEntries->count(),
                    'average_daily_hours' => round($weekEntries->sum('hours') / 7, 2),
                ];
            }),
        ];
    }

    private function getMonthlyReport($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Monthly Report',
            'date_range' => "$startDate to $endDate",
            'total_hours' => $entries->sum('hours'),
            'total_entries' => $entries->count(),
            'average_daily_hours' => round($entries->sum('hours') / max(1, $entries->groupBy('entry_date')->count()), 2),
            'daily_breakdown' => $entries->groupBy('entry_date')->map(function ($dayEntries) {
                return [
                    'date' => $dayEntries->first()->entry_date->format('Y-m-d'),
                    'hours' => $dayEntries->sum('hours'),
                    'entries' => $dayEntries->count(),
                ];
            }),
        ];
    }

    private function getProjectOverview($startDate, $endDate, $user)
    {
        $projects = Project::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Project Overview',
            'date_range' => "$startDate to $endDate",
            'total_projects' => $projects->count(),
            'projects' => $projects->map(function ($project) {
                return [
                    'name' => $project->name,
                    'status' => $project->status,
                    'progress' => $project->progress,
                    'start_date' => $project->start_date,
                    'due_date' => $project->due_date,
                    'description' => $project->description,
                ];
            }),
        ];
    }

    private function getProjectTimeAllocation($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get()
            ->groupBy('project_id');

        return [
            'title' => 'Time Allocation by Project',
            'date_range' => "$startDate to $endDate",
            'allocations' => $entries->map(function ($projectEntries, $projectId) {
                $project = Project::find($projectId);
                $totalHours = $projectEntries->sum('hours');
                return [
                    'project' => $project?->name ?? 'Unassigned',
                    'hours' => $totalHours,
                    'entries' => $projectEntries->count(),
                ];
            }),
        ];
    }

    private function getProjectStatus($startDate, $endDate, $user)
    {
        $projects = Project::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Project Status Report',
            'date_range' => "$startDate to $endDate",
            'projects' => $projects->map(function ($project) {
                return [
                    'name' => $project->name,
                    'status' => $project->status,
                    'progress' => $project->progress . '%',
                    'completion_date' => $project->completed_at,
                ];
            }),
        ];
    }

    private function getProjectBudget($startDate, $endDate, $user)
    {
        $projects = Project::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('budget')
            ->get();

        return [
            'title' => 'Project Budget Report',
            'date_range' => "$startDate to $endDate",
            'projects' => $projects->map(function ($project) {
                return [
                    'name' => $project->name,
                    'budget' => $project->budget?->allocated_budget ?? 0,
                    'spent' => $project->budget?->spent_amount ?? 0,
                ];
            }),
        ];
    }

    private function getProductivitySummary($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        $totalDays = $entries->groupBy('entry_date')->count();
        $totalHours = $entries->sum('hours');

        return [
            'title' => 'Productivity Summary',
            'date_range' => "$startDate to $endDate",
            'total_hours' => $totalHours,
            'working_days' => $totalDays,
            'average_daily_hours' => $totalDays > 0 ? round($totalHours / $totalDays, 2) : 0,
            'total_entries' => $entries->count(),
        ];
    }

    private function getTeamPerformance($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Team Performance',
            'date_range' => "$startDate to $endDate",
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'total_hours' => $entries->sum('hours'),
                'entries_count' => $entries->count(),
            ],
        ];
    }

    private function getCustomExport($startDate, $endDate, $user)
    {
        $entries = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Custom Data Export',
            'date_range' => "$startDate to $endDate",
            'data' => $entries->toArray(),
        ];
    }

    private function getFullBackup($user)
    {
        return [
            'title' => 'Full Data Backup',
            'user' => $user->toArray(),
            'time_entries' => TimeEntry::where('user_id', $user->id)->get()->toArray(),
            'projects' => Project::where('user_id', $user->id)->get()->toArray(),
            'backup_date' => now()->toIso8601String(),
        ];
    }

    private function exportPDF($data, $reportType)
    {
        // For PDF export, we'll return JSON that can be processed by frontend
        // In production, use a library like TCPDF or DomPDF
        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"$filename\"")
            ->header('Content-Type', 'application/pdf');
    }

    private function exportExcel($data, $reportType)
    {
        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        // Convert to CSV format for Excel
        $csv = $this->arrayToCSV($data);
        
        return response($csv)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function exportWord($data, $reportType)
    {
        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . '.docx';
        
        $html = $this->arrayToHTML($data);
        
        return response($html)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function exportCSV($data, $reportType)
    {
        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $csv = $this->arrayToCSV($data);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function exportJSON($data, $reportType)
    {
        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function exportZIP($data, $reportType)
    {
        $filename = "backup_" . now()->format('Y-m-d_H-i-s') . '.zip';
        
        // Create a ZIP file with JSON data
        $json = json_encode($data, JSON_PRETTY_PRINT);
        
        return response($json)
            ->header('Content-Type', 'application/zip')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function arrayToCSV($data)
    {
        $csv = '';
        
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $csv .= $key . "\n";
                    $csv .= $this->arrayToCSV($value);
                } else {
                    $csv .= "$key,$value\n";
                }
            }
        }
        
        return $csv;
    }

    private function arrayToHTML($data)
    {
        $html = '<html><body>';
        $html .= '<h1>' . ($data['title'] ?? 'Report') . '</h1>';
        $html .= '<p>Generated: ' . now()->format('Y-m-d H:i:s') . '</p>';
        
        if (isset($data['date_range'])) {
            $html .= '<p><strong>Date Range:</strong> ' . $data['date_range'] . '</p>';
        }
        
        $html .= '<table border="1" cellpadding="10">';
        
        foreach ($data as $key => $value) {
            if (is_array($value) && $key !== 'title' && $key !== 'date_range') {
                $html .= '<tr><td colspan="2"><strong>' . ucfirst(str_replace('_', ' ', $key)) . '</strong></td></tr>';
                foreach ($value as $item) {
                    if (is_array($item)) {
                        foreach ($item as $k => $v) {
                            $html .= '<tr><td>' . ucfirst(str_replace('_', ' ', $k)) . '</td><td>' . (is_array($v) ? json_encode($v) : $v) . '</td></tr>';
                        }
                    } else {
                        $html .= '<tr><td colspan="2">' . $item . '</td></tr>';
                    }
                }
            }
        }
        
        $html .= '</table>';
        $html .= '</body></html>';
        
        return $html;
    }
}
