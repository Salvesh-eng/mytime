<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show user dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $hoursThisWeek = TimeEntry::where('user_id', $user->id)
            ->whereBetween('entry_date', [$startOfWeek, $endOfWeek])
            ->where('status', 'approved')
            ->sum('hours');

        $todayEntry = TimeEntry::where('user_id', $user->id)
            ->whereDate('entry_date', Carbon::today())
            ->first();

        $recentEntries = TimeEntry::where('user_id', $user->id)
            ->latest('entry_date')
            ->limit(5)
            ->get();

        // Get project completion data for current month
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->clone()->startOfMonth();
        $endOfMonth = $currentMonth->clone()->endOfMonth();
        
        $projectCompletionThisMonth = $this->getProjectCompletionByDay($user->id, $startOfMonth, $endOfMonth);
        $projectCompletionThisYear = $this->getProjectCompletionByMonth($user->id, $currentMonth->year);

        return view('user.dashboard', [
            'hoursThisWeek' => $hoursThisWeek,
            'todayEntry' => $todayEntry,
            'recentEntries' => $recentEntries,
            'projectCompletionThisMonth' => $projectCompletionThisMonth,
            'projectCompletionThisYear' => $projectCompletionThisYear,
        ]);
    }

    /**
     * Get project completion count by day for a date range.
     */
    private function getProjectCompletionByDay($userId, $startDate, $endDate)
    {
        $data = [];
        $current = $startDate->clone();
        
        while ($current <= $endDate) {
            $count = TimeEntry::where('user_id', $userId)
                ->whereDate('entry_date', $current)
                ->where('status', 'approved')
                ->distinct('project_id')
                ->count('project_id');
            
            $data[] = [
                'date' => $current->format('Y-m-d'),
                'day' => $current->format('M d'),
                'count' => $count,
            ];
            
            $current->addDay();
        }
        
        return $data;
    }

    /**
     * Get project completion count by month for a year.
     */
    private function getProjectCompletionByMonth($userId, $year)
    {
        $data = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $count = TimeEntry::where('user_id', $userId)
                ->whereBetween('entry_date', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved')
                ->distinct('project_id')
                ->count('project_id');
            
            $data[] = [
                'month' => $startOfMonth->format('M'),
                'count' => $count,
            ];
        }
        
        return $data;
    }

    /**
     * Get dashboard metrics via API for auto-refresh.
     */
    public function getMetrics()
    {
        $user = auth()->user();
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');
        
        $query = TimeEntry::where('user_id', $user->id);

        if ($startDate && $endDate) {
            $query->whereBetween('entry_date', [$startDate, $endDate])
                  ->where('status', 'approved');
            $hoursThisWeek = $query->sum('hours');
        } else {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $hoursThisWeek = TimeEntry::where('user_id', $user->id)
                ->whereBetween('entry_date', [$startOfWeek, $endOfWeek])
                ->where('status', 'approved')
                ->sum('hours');
        }

        $todayEntry = TimeEntry::where('user_id', $user->id)
            ->whereDate('entry_date', Carbon::today())
            ->first();

        $todayStatus = $todayEntry ? ucfirst($todayEntry->status) : 'No Entry';

        return response()->json([
            'hoursThisWeek' => number_format($hoursThisWeek, 2),
            'todayStatus' => $todayStatus,
            'timestamp' => Carbon::now()->format('H:i:s'),
        ]);
    }
}
