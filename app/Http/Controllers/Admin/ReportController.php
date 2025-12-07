<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Project;
use App\Models\CustomReport;
use App\Models\ReportExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Show reports page with all report types.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();

        // Hours by user
        $hoursByUser = TimeEntry::selectRaw('user_id, users.name, SUM(hours) as total_hours')
            ->join('users', 'time_entries.user_id', '=', 'users.id')
            ->whereBetween('time_entries.entry_date', [$dateFrom, $dateTo])
            ->where('time_entries.status', 'approved')
            ->groupBy('time_entries.user_id', 'users.name')
            ->get();

        // Hours by date
        $hoursByDate = TimeEntry::selectRaw('entry_date, SUM(hours) as total_hours')
            ->whereBetween('time_entries.entry_date', [$dateFrom, $dateTo])
            ->where('time_entries.status', 'approved')
            ->groupBy('time_entries.entry_date')
            ->orderBy('time_entries.entry_date')
            ->get();

        // Utilization rates
        $utilizationData = $this->getUtilizationData($dateFrom, $dateTo);

        // Project profitability
        $profitabilityData = $this->getProjectProfitability($dateFrom, $dateTo);

        // Time variance
        $varianceData = $this->getTimeVariance($dateFrom, $dateTo);

        // Custom reports
        $customReports = CustomReport::where('is_template', false)->get();

        return view('admin.reports.index', [
            'hoursByUser' => $hoursByUser,
            'hoursByDate' => $hoursByDate,
            'utilizationData' => $utilizationData,
            'profitabilityData' => $profitabilityData,
            'varianceData' => $varianceData,
            'customReports' => $customReports,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Show custom report builder.
     */
    public function builder(Request $request)
    {
        $availableMetrics = CustomReport::getAvailableMetrics();
        $availableFilters = CustomReport::getAvailableFilters();
        $availableGrouping = CustomReport::getAvailableGrouping();
        $users = User::where('status', 'active')->get();
        $projects = Project::where('is_archived', false)->get();

        return view('admin.reports.builder', [
            'availableMetrics' => $availableMetrics,
            'availableFilters' => $availableFilters,
            'availableGrouping' => $availableGrouping,
            'users' => $users,
            'projects' => $projects,
        ]);
    }

    /**
     * Store a custom report.
     */
    public function storeCustomReport(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'metrics' => 'required|array|min:1',
            'filters' => 'nullable|array',
            'grouping' => 'nullable|array',
            'branding' => 'nullable|array',
            'is_template' => 'boolean',
        ]);

        $validated['created_by_user_id'] = auth()->id();

        $customReport = CustomReport::create($validated);

        return redirect()->route('admin.reports.show', $customReport)
            ->with('success', 'Custom report created successfully!');
    }

    /**
     * Show a custom report.
     */
    public function showCustomReport(CustomReport $customReport)
    {
        $data = $this->generateReportData($customReport);

        return view('admin.reports.show', [
            'customReport' => $customReport,
            'data' => $data,
        ]);
    }

    /**
     * Generate report data based on custom report configuration.
     */
    private function generateReportData(CustomReport $customReport)
    {
        $metrics = $customReport->metrics ?? [];
        $filters = $customReport->filters ?? [];
        $grouping = $customReport->grouping ?? [];

        $data = [];

        foreach ($metrics as $metric) {
            $data[$metric] = $this->getMetricData($metric, $filters, $grouping);
        }

        return $data;
    }

    /**
     * Get specific metric data.
     */
    private function getMetricData($metric, $filters, $grouping)
    {
        $dateFrom = isset($filters['date_from']) ? Carbon::parse($filters['date_from']) : Carbon::now()->startOfMonth();
        $dateTo = isset($filters['date_to']) ? Carbon::parse($filters['date_to']) : Carbon::now()->endOfMonth();

        $query = TimeEntry::whereBetween('time_entries.entry_date', [$dateFrom, $dateTo])
            ->where('time_entries.status', 'approved');

        // Apply filters
        if (isset($filters['user_id'])) {
            $query->where('time_entries.user_id', $filters['user_id']);
        }
        if (isset($filters['project_id'])) {
            $query->where('time_entries.project_id', $filters['project_id']);
        }

        switch ($metric) {
            case 'hours_by_user':
                return $query->selectRaw('time_entries.user_id, users.name, SUM(time_entries.hours) as total_hours')
                    ->join('users', 'time_entries.user_id', '=', 'users.id')
                    ->groupBy('time_entries.user_id', 'users.name')
                    ->get();

            case 'hours_by_project':
                return $query->selectRaw('time_entries.project_id, projects.name, SUM(time_entries.hours) as total_hours')
                    ->join('projects', 'time_entries.project_id', '=', 'projects.id')
                    ->groupBy('time_entries.project_id', 'projects.name')
                    ->get();

            case 'hours_by_date':
                return $query->selectRaw('time_entries.entry_date, SUM(time_entries.hours) as total_hours')
                    ->groupBy('time_entries.entry_date')
                    ->orderBy('time_entries.entry_date')
                    ->get();

            case 'utilization_rate':
                return $this->getUtilizationData($dateFrom, $dateTo);

            case 'project_profitability':
                return $this->getProjectProfitability($dateFrom, $dateTo);

            case 'time_variance':
                return $this->getTimeVariance($dateFrom, $dateTo);

            default:
                return [];
        }
    }

    /**
     * Get utilization rates for employees.
     */
    private function getUtilizationData($dateFrom, $dateTo)
    {
        $users = User::where('status', 'active')->get();
        $utilizationData = [];

        foreach ($users as $user) {
            $billableHours = TimeEntry::where('user_id', $user->id)
                ->whereBetween('entry_date', [$dateFrom, $dateTo])
                ->where('status', 'approved')
                ->sum('hours');

            $totalHours = TimeEntry::where('user_id', $user->id)
                ->whereBetween('entry_date', [$dateFrom, $dateTo])
                ->where('status', 'approved')
                ->sum('hours');

            $utilizationRate = $totalHours > 0 ? round(($billableHours / $totalHours) * 100, 2) : 0;

            $utilizationData[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'billable_hours' => $billableHours,
                'total_hours' => $totalHours,
                'utilization_rate' => $utilizationRate,
                'capacity_available' => max(0, (40 * $dateFrom->diffInWeeks($dateTo)) - $totalHours),
            ];
        }

        return $utilizationData;
    }

    /**
     * Get project profitability analysis.
     */
    private function getProjectProfitability($dateFrom, $dateTo)
    {
        $projects = Project::with('budget', 'timeEntries')
            ->where('is_archived', false)
            ->get();

        $profitabilityData = [];

        foreach ($projects as $project) {
            $totalHours = $project->timeEntries()
                ->whereBetween('entry_date', [$dateFrom, $dateTo])
                ->where('status', 'approved')
                ->sum('hours');

            $estimatedCost = $totalHours * 50; // Default hourly rate
            $actualRevenue = $project->budget ? $project->budget->allocated_budget : 0;
            $profitability = $actualRevenue - $estimatedCost;

            $profitabilityData[] = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'total_hours' => $totalHours,
                'estimated_cost' => $estimatedCost,
                'actual_revenue' => $actualRevenue,
                'profitability' => $profitability,
                'margin_percentage' => $actualRevenue > 0 ? round(($profitability / $actualRevenue) * 100, 2) : 0,
            ];
        }

        return $profitabilityData;
    }

    /**
     * Get time variance (estimated vs actual).
     */
    private function getTimeVariance($dateFrom, $dateTo)
    {
        $projects = Project::with('timeEntries')
            ->where('is_archived', false)
            ->get();

        $varianceData = [];

        foreach ($projects as $project) {
            $actualHours = $project->timeEntries()
                ->whereBetween('entry_date', [$dateFrom, $dateTo])
                ->where('status', 'approved')
                ->sum('hours');

            $estimatedHours = $project->estimated_hours ?? 0;
            $variance = $actualHours - $estimatedHours;
            $variancePercentage = $estimatedHours > 0 ? round(($variance / $estimatedHours) * 100, 2) : 0;

            $varianceData[] = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'estimated_hours' => $estimatedHours,
                'actual_hours' => $actualHours,
                'variance' => $variance,
                'variance_percentage' => $variancePercentage,
                'status' => $variancePercentage > 10 ? 'over' : ($variancePercentage < -10 ? 'under' : 'on-track'),
            ];
        }

        return $varianceData;
    }

    /**
     * Export report as CSV.
     */
    public function exportCsv(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();

        $entries = TimeEntry::with('user')
            ->whereBetween('entry_date', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->orderBy('entry_date')
            ->get();

        $filename = 'time_report_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($entries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'User', 'Start Time', 'End Time', 'Hours', 'Description']);

            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->entry_date->format('Y-m-d'),
                    $entry->user->name,
                    $entry->start_time,
                    $entry->end_time,
                    $entry->hours,
                    $entry->description,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export custom report as PDF.
     */
    public function exportPdf(CustomReport $customReport, Request $request)
    {
        $data = $this->generateReportData($customReport);
        $branding = $customReport->branding ?? [];

        // Create PDF content
        $html = $this->generatePdfHtml($customReport, $data, $branding);

        $filename = 'report_' . $customReport->id . '_' . now()->format('Y-m-d-His') . '.pdf';

        // Store export record
        ReportExport::create([
            'custom_report_id' => $customReport->id,
            'format' => 'pdf',
            'filename' => $filename,
            'file_path' => 'reports/' . $filename,
            'exported_by_user_id' => auth()->id(),
        ]);

        // Return PDF download
        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, ['Content-Type' => 'application/pdf']);
    }

    /**
     * Export custom report as Excel.
     */
    public function exportExcel(CustomReport $customReport, Request $request)
    {
        $data = $this->generateReportData($customReport);

        $filename = 'report_' . $customReport->id . '_' . now()->format('Y-m-d-His') . '.xlsx';

        // Store export record
        ReportExport::create([
            'custom_report_id' => $customReport->id,
            'format' => 'excel',
            'filename' => $filename,
            'file_path' => 'reports/' . $filename,
            'exported_by_user_id' => auth()->id(),
        ]);

        // Generate Excel content
        return $this->generateExcelResponse($customReport, $data, $filename);
    }

    /**
     * Generate PDF HTML content.
     */
    private function generatePdfHtml(CustomReport $customReport, $data, $branding)
    {
        $html = '<html><head><style>';
        $html .= 'body { font-family: Arial, sans-serif; margin: 20px; }';
        $html .= 'h1 { color: ' . ($branding['primary_color'] ?? '#333') . '; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin: 20px 0; }';
        $html .= 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
        $html .= 'th { background-color: ' . ($branding['header_color'] ?? '#f2f2f2') . '; }';
        $html .= '</style></head><body>';

        $html .= '<h1>' . htmlspecialchars($customReport->name) . '</h1>';
        if ($customReport->description) {
            $html .= '<p>' . htmlspecialchars($customReport->description) . '</p>';
        }
        $html .= '<p>Generated on: ' . now()->format('Y-m-d H:i:s') . '</p>';

        foreach ($data as $metric => $records) {
            $html .= '<h2>' . ucfirst(str_replace('_', ' ', $metric)) . '</h2>';
            if (is_array($records) && count($records) > 0) {
                $html .= '<table>';
                $html .= '<thead><tr>';
                foreach (array_keys((array)$records[0]) as $key) {
                    $html .= '<th>' . ucfirst(str_replace('_', ' ', $key)) . '</th>';
                }
                $html .= '</tr></thead>';
                $html .= '<tbody>';
                foreach ($records as $record) {
                    $html .= '<tr>';
                    foreach ((array)$record as $value) {
                        $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</tbody></table>';
            }
        }

        $html .= '</body></html>';
        return $html;
    }

    /**
     * Generate Excel response.
     */
    private function generateExcelResponse(CustomReport $customReport, $data, $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($customReport, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Report: ' . $customReport->name]);
            fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            foreach ($data as $metric => $records) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $metric))]);
                if (is_array($records) && count($records) > 0) {
                    fputcsv($file, array_keys((array)$records[0]));
                    foreach ($records as $record) {
                        fputcsv($file, (array)$record);
                    }
                }
                fputcsv($file, []);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * List all custom reports.
     */
    public function listCustomReports()
    {
        $customReports = CustomReport::where('is_template', false)
            ->with('createdBy')
            ->latest()
            ->paginate(15);

        return view('admin.reports.list', [
            'customReports' => $customReports,
        ]);
    }

    /**
     * Delete a custom report.
     */
    public function deleteCustomReport(CustomReport $customReport)
    {
        $customReport->delete();

        return redirect()->route('admin.reports.list')
            ->with('success', 'Report deleted successfully!');
    }
}
