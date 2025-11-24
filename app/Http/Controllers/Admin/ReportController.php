<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show reports page.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();

        // Hours by user
        $hoursByUser = TimeEntry::selectRaw('user_id, users.name, SUM(hours) as total_hours')
            ->join('users', 'time_entries.user_id', '=', 'users.id')
            ->whereBetween('entry_date', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->groupBy('user_id', 'users.name')
            ->get();

        // Hours by date
        $hoursByDate = TimeEntry::selectRaw('entry_date, SUM(hours) as total_hours')
            ->whereBetween('entry_date', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->groupBy('entry_date')
            ->orderBy('entry_date')
            ->get();

        return view('admin.reports.index', [
            'hoursByUser' => $hoursByUser,
            'hoursByDate' => $hoursByDate,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
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
}
