@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<style>
    :root {
        --soft-pink: #FFB3D9;
        --soft-blue: #B3D9FF;
        --soft-green: #B3FFB3;
        --soft-orange: #FFD9B3;
        --soft-purple: #D9B3FF;
        --soft-peach: #FFCCB3;
        --soft-mint: #B3FFD9;
        --soft-lavender: #E6D9FF;
        --light-green: #C8E6C9;
        --light-pink: #F8BBD0;
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-card h3 {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin: 0 0 12px 0;
    }

    .stat-card .value {
        font-size: 32px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .stat-card .comparison {
        font-size: 12px;
        font-weight: 600;
    }

    .chart-widget {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .chart-header h2 {
        margin: 0;
        font-size: 18px;
        color: #0F172A;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-action-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .quick-action-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .quick-action-card h3 {
        font-size: 14px;
        color: #0F172A;
        font-weight: 600;
        margin: 12px 0 8px 0;
    }

    .quick-action-card p {
        font-size: 12px;
        color: #6B7280;
        margin: 0 0 12px 0;
    }

    .quick-action-card a {
        display: inline-block;
        padding: 10px 18px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        color: #0c4a6e;
        text-decoration: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
        border: 1px solid rgba(179, 217, 255, 0.5);
    }

    .quick-action-card a:hover {
        box-shadow: 0 4px 12px rgba(179, 217, 255, 0.3);
    }

    .progress-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .progress-card h3 {
        font-size: 14px;
        color: #0F172A;
        font-weight: 600;
        margin: 0 0 12px 0;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .progress-fill {
        height: 100%;
        transition: width 0.3s ease;
        border-radius: 4px;
    }

    .progress-fill.green {
        background: linear-gradient(90deg, #16A34A, #15803d);
    }

    .progress-fill.blue {
        background: linear-gradient(90deg, #06B6D4, #0891b2);
    }

    .progress-fill.orange {
        background: linear-gradient(90deg, #F59E0B, #d97706);
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 6px;
    }

    .activity-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #2563EB;
        margin-bottom: 12px;
    }

    .activity-item:last-child {
        margin-bottom: 0;
    }

    .activity-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-content .title {
        font-weight: 600;
        color: #0F172A;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .activity-content .description {
        color: #6B7280;
        font-size: 12px;
        margin: 0;
    }

    .activity-content .time {
        color: #9ca3af;
        font-size: 11px;
        margin-top: 4px;
    }

    .header-section {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(255, 179, 217, 0.15) 100%);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(179, 255, 179, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);
        margin-bottom: 32px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-left h1 {
        font-size: 26px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }

    .header-left p {
        color: #6B7280;
        font-size: 14px;
        margin: 0;
    }

    .header-right {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
        text-decoration: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(179, 255, 179, 0.3);
        border: 1px solid rgba(179, 255, 179, 0.5);
    }

    .btn-primary:hover {
        box-shadow: 0 6px 16px rgba(179, 255, 179, 0.4);
        transform: translateY(-2px);
    }

    .last-updated {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.3) 0%, rgba(230, 217, 255, 0.3) 100%);
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid rgba(179, 217, 255, 0.4);
        text-align: right;
    }

    .last-updated .label {
        font-size: 11px;
        color: #6B7280;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .last-updated .time {
        font-size: 14px;
        font-weight: 600;
        color: #0c4a6e;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-buttons {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        border: 2px solid #e5e7eb;
        background-color: white;
        color: #0F172A;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-btn.active {
        border-color: #2563EB;
        background-color: #2563EB;
        color: white;
    }

    .filter-btn:hover {
        border-color: #2563EB;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        background: linear-gradient(135deg, #f0f9ff 0%, #fef3c7 100%);
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6B7280;
        font-size: 14px;
        margin-bottom: 24px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-state-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .empty-state-buttons a {
        display: inline-block;
        padding: 12px 24px;
        background-color: #2563EB;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .empty-state-buttons a:hover {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
    }

    .empty-state-buttons a.secondary {
        background-color: #06B6D4;
    }

    .empty-state-buttons a.secondary:hover {
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .recent-entries-table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-entries-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .recent-entries-table th {
        padding: 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .recent-entries-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
        color: #0F172A;
    }

    .recent-entries-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-pending {
        background: rgba(255, 217, 179, 0.3);
        color: #92400e;
    }

    .badge-approved {
        background: rgba(179, 255, 179, 0.3);
        color: #15803d;
    }

    .badge-rejected {
        background: rgba(255, 179, 217, 0.3);
        color: #9d174d;
    }

    .footer-section {
        margin-top: 40px;
        padding: 24px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.15) 0%, rgba(179, 255, 217, 0.1) 50%, rgba(230, 217, 255, 0.15) 100%);
        border-radius: 16px;
        border: 1px solid rgba(179, 217, 255, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.1);
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .footer-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-left-icon {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        padding: 10px;
        border-radius: 12px;
        font-size: 18px;
    }

    .footer-left p {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #0F172A;
    }

    .footer-right {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%);
        color: #0c4a6e;
        text-decoration: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(179, 217, 255, 0.4);
        transition: all 0.3s;
    }

    .footer-link:hover {
        box-shadow: 0 4px 12px rgba(179, 217, 255, 0.3);
    }

    </style>

<!-- Header Section -->
<div class="header-section">
    <div class="header-content">
        <div class="header-left">
            <h1>👋 Welcome Back</h1>
            <p>Track your time and manage your productivity</p>
        </div>
        <div class="header-right">
            <a href="/add-time" class="btn-primary">▶ Quick Timer</a>
            <div class="last-updated">
                <div class="label">Last updated</div>
                <div class="time" id="last-updated-time">Just now</div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics Section -->
<div class="stats-grid">
    <!-- Hours This Week Card -->
    <div class="stat-card" style="border-left: 4px solid #16A34A;">
        <h3>⏱️ Hours This Week</h3>
        <div class="value" data-metric="hoursThisWeek">{{ number_format($hoursThisWeek, 2) }}</div>
        <div class="comparison" style="color: #16A34A;">↑ 8% vs last week</div>
    </div>

    <!-- Today's Hours Card -->
    <div class="stat-card" style="border-left: 4px solid #06B6D4;">
        <h3>📅 Today's Hours</h3>
        <div class="value" data-metric="todayHours">{{ number_format($todayHours ?? 0, 2) }}</div>
        <div class="comparison" style="color: #06B6D4;">Target: 8 hours</div>
    </div>

    <!-- Pending Approvals Card -->
    <div class="stat-card" style="border-left: 4px solid #F59E0B;">
        <h3>⏳ Pending Approvals</h3>
        <div class="value" data-metric="pendingApprovals">{{ $pendingApprovals ?? 0 }}</div>
        <div class="comparison" style="color: #F59E0B;">Awaiting review</div>
    </div>

    <!-- Total Projects Card -->
    <div class="stat-card" style="border-left: 4px solid #8B5CF6;">
        <h3>📁 Active Projects</h3>
        <div class="value" data-metric="activeProjects">{{ $activeProjects ?? 0 }}</div>
        <div class="comparison" style="color: #8B5CF6;">In progress</div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="filter-section">
    <div class="filter-buttons">
        <span style="font-weight: 600; color: #0F172A; font-size: 14px;">Filter by date:</span>
        <button onclick="setDateRange('today')" class="date-range-btn filter-btn active" data-range="today">Today</button>
        <button onclick="setDateRange('week')" class="date-range-btn filter-btn" data-range="week">This Week</button>
        <button onclick="setDateRange('month')" class="date-range-btn filter-btn" data-range="month">This Month</button>
        <button onclick="setDateRange('all')" class="date-range-btn filter-btn" data-range="all">All Time</button>
        <div style="margin-left: auto; display: flex; gap: 8px; align-items: center;">
            <input type="date" id="custom-date-start" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            <span style="color: #6B7280;">to</span>
            <input type="date" id="custom-date-end" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            <button onclick="setDateRange('custom')" style="padding: 8px 16px; border: 2px solid #06B6D4; background-color: #06B6D4; color: white; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">Apply</button>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Weekly Hours Chart -->
    <div class="chart-widget">
        <div class="chart-header">
            <h2>📊 Weekly Hours</h2>
            <span style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--light-green) 100%); padding: 8px 14px; border-radius: 8px; font-size: 12px; color: #0F172A; font-weight: 600;">{{ now()->format('M Y') }}</span>
        </div>
        <div style="position: relative; height: 300px;">
            <canvas id="weeklyHoursChart"></canvas>
        </div>
    </div>

    <!-- Project Completion Chart -->
    <div class="chart-widget">
        <div class="chart-header">
            <h2>📈 Project Status</h2>
            <span style="background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%); padding: 8px 14px; border-radius: 8px; font-size: 12px; color: #0F172A; font-weight: 600;">{{ $totalProjects ?? 0 }} Total</span>
        </div>
        <div style="position: relative; height: 300px; display: flex; align-items: center; justify-content: center;">
            <canvas id="projectStatusChart" style="max-width: 250px; max-height: 250px;"></canvas>
        </div>
    </div>
</div>

<!-- Yearly Completion Chart -->
<div class="chart-widget">
    <div class="chart-header">
        <h2>📈 Project Completion This Year</h2>
        <span style="background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); padding: 8px 14px; border-radius: 8px; font-size: 12px; color: #0F172A; font-weight: 600;">{{ now()->format('Y') }}</span>
    </div>
    <div style="position: relative; height: 350px;">
        <canvas id="yearlyChart"></canvas>
    </div>
</div>

<!-- Quick Actions Section -->
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
        <span style="background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); padding: 8px; border-radius: 10px; font-size: 16px;">⚡</span>
        Quick Actions
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        <!-- Add Time Entry -->
        <div class="quick-action-card">
            <div style="font-size: 32px;">⏱️</div>
            <h3>Add Time Entry</h3>
            <p>Log your work hours and track time spent on projects</p>
            <a href="/add-time">Add Entry</a>
        </div>

        <!-- View All Logs -->
        <div class="quick-action-card">
            <div style="font-size: 32px;">📋</div>
            <h3>View All Logs</h3>
            <p>Review all your time entries and manage submissions</p>
            <a href="/my-logs">View Logs</a>
        </div>

        <!-- My Projects -->
        <div class="quick-action-card">
            <div style="font-size: 32px;">📁</div>
            <h3>My Projects</h3>
            <p>Check your assigned projects and their progress</p>
            <a href="/my-projects">View Projects</a>
        </div>

        <!-- Reports -->
        <div class="quick-action-card">
            <div style="font-size: 32px;">📊</div>
            <h3>View Reports</h3>
            <p>Generate and view your productivity reports</p>
            <a href="/reports">View Reports</a>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="chart-widget">
    <div class="chart-header">
        <h2>🔔 Recent Activity</h2>
        <a href="/notifications" style="font-size: 12px; color: #2563EB; text-decoration: none; font-weight: 600;">View All</a>
    </div>
    <div id="activity-feed-container">
        <div style="text-align: center; padding: 32px 20px; color: #9ca3af;">
            <p style="font-size: 14px; margin: 0;">📭 No recent activities yet</p>
        </div>
    </div>
</div>

<!-- Recent Entries Section -->
<div class="chart-widget">
    <div class="chart-header">
        <h2>📝 Recent Entries</h2>
        <a href="/my-logs" style="font-size: 12px; color: #2563EB; text-decoration: none; font-weight: 600;">View All</a>
    </div>
    @if($recentEntries->count() > 0)
        <div style="overflow-x: auto;">
            <table class="recent-entries-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEntries as $entry)
                        <tr>
                            <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                            <td>{{ $entry->start_time }}</td>
                            <td>{{ $entry->end_time }}</td>
                            <td>{{ number_format($entry->hours, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $entry->status }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                            <td>
                                @if($entry->status === 'pending')
                                    <a href="/my-logs/{{ $entry->id }}/edit" style="color: #2563EB; text-decoration: none; font-size: 12px; font-weight: 600;">Edit</a>
                                @else
                                    <span style="color: #9ca3af; font-size: 12px;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">⏱️</div>
            <h3>Start Tracking Your Time</h3>
            <p>You haven't logged any time entries yet. Begin tracking your work now to monitor your productivity and time management.</p>
            <div class="empty-state-buttons">
                <a href="/add-time">▶ Start Tracking Time</a>
                <a href="/my-logs" class="secondary">View All Logs</a>
            </div>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    let currentDateRange = 'today';
    let weeklyChartInstance = null;
    let projectStatusChartInstance = null;
    let yearlyChartInstance = null;
    
    const REFRESH_INTERVAL = 30000; // 30 seconds

    const colorPalette = [
        '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
        '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B88B', '#52C9A3',
        '#FF8C94', '#A8E6CF', '#FFD3B6', '#FFAAA5', '#FF8B94'
    ];

    function getRandomColor() {
        return colorPalette[Math.floor(Math.random() * colorPalette.length)];
    }

    function initializeCharts() {
        const projectData = @json($projectCompletionThisYear);
        
        // Initialize Weekly Hours Chart
        initializeWeeklyHoursChart();
        
        // Initialize Project Status Chart
        initializeProjectStatusChart();
        
        // Initialize Yearly Chart
        initializeYearlyChart(projectData);
    }

    function initializeWeeklyHoursChart() {
        const ctx = document.getElementById('weeklyHoursChart');
        if (!ctx) return;

        const weeklyData = [8.5, 7.2, 8.0, 6.5, 9.0, 7.8, 0];
        const weekLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        const canvasCtx = ctx.getContext('2d');
        const gradient = canvasCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(179, 255, 179, 0.3)');
        gradient.addColorStop(1, 'rgba(179, 255, 179, 0.01)');

        if (weeklyChartInstance) {
            weeklyChartInstance.destroy();
        }

        weeklyChartInstance = new Chart(canvasCtx, {
            type: 'bar',
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Hours Logged',
                    data: weeklyData,
                    backgroundColor: gradient,
                    borderColor: '#16A34A',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(22, 163, 74, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#0F172A',
                            font: { size: 12, weight: 'bold' },
                            padding: 15,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        borderColor: '#16A34A',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return 'Hours: ' + context.parsed.y.toFixed(1);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        ticks: {
                            stepSize: 2,
                            font: { size: 11 },
                            color: '#6B7280'
                        },
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#6B7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    function initializeProjectStatusChart() {
        const ctx = document.getElementById('projectStatusChart');
        if (!ctx) return;

        const statusData = [5, 3, 2];
        const statusLabels = ['In Progress', 'Completed', 'On Hold'];
        const statusColors = ['#06B6D4', '#16A34A', '#F59E0B'];

        const canvasCtx = ctx.getContext('2d');

        if (projectStatusChartInstance) {
            projectStatusChartInstance.destroy();
        }

        projectStatusChartInstance = new Chart(canvasCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#0F172A',
                            font: { size: 12, weight: 'bold' },
                            padding: 15,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    function initializeYearlyChart(projectData) {
        const ctx = document.getElementById('yearlyChart');
        if (!ctx) return;

        const yearlyLabels = projectData.map(d => d.month);
        const yearlyCounts = projectData.map(d => d.count);
        const yearlyColors = projectData.map(() => getRandomColor());

        const canvasCtx = ctx.getContext('2d');
        const gradient = canvasCtx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.4)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

        if (yearlyChartInstance) {
            yearlyChartInstance.destroy();
        }

        yearlyChartInstance = new Chart(canvasCtx, {
            type: 'line',
            data: {
                labels: yearlyLabels,
                datasets: [{
                    label: 'Projects Completed',
                    data: yearlyCounts,
                    borderColor: '#2563EB',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: yearlyColors,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: yearlyColors,
                    pointHoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 12, weight: 'bold' },
                            color: '#0F172A',
                            padding: 15,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        borderColor: '#2563EB',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return 'Projects: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 },
                            color: '#6B7280'
                        },
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#6B7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    function setDateRange(range) {
        currentDateRange = range;
        
        document.querySelectorAll('.date-range-btn').forEach(btn => {
            if (btn.getAttribute('data-range') === range) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        updateDashboardMetrics();
    }

    function getDateRangeParams() {
        const today = new Date();
        let startDate, endDate = new Date().toISOString().split('T')[0];
        
        switch(currentDateRange) {
            case 'today':
                startDate = endDate;
                break;
            case 'week':
                const first = today.getDate() - today.getDay();
                startDate = new Date(today.setDate(first)).toISOString().split('T')[0];
                break;
            case 'month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                break;
            case 'custom':
                startDate = document.getElementById('custom-date-start').value;
                endDate = document.getElementById('custom-date-end').value;
                if (!startDate || !endDate) {
                    alert('Please select both start and end dates');
                    return null;
                }
                break;
            case 'all':
            default:
                startDate = null;
                endDate = null;
        }
        
        return { startDate, endDate };
    }

    function updateDashboardMetrics() {
        const params = getDateRangeParams();
        if (currentDateRange === 'custom' && !params) return;
        
        let url = '/api/user/dashboard-metrics';
        if (params && params.startDate) {
            url += `?startDate=${params.startDate}&endDate=${params.endDate}`;
        }
        
        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            const hoursElement = document.querySelector('[data-metric="hoursThisWeek"]');
            if (hoursElement && data.hoursThisWeek) {
                hoursElement.textContent = data.hoursThisWeek;
            }
            
            const lastUpdated = document.getElementById('last-updated-time');
            if (lastUpdated && data.timestamp) {
                lastUpdated.textContent = data.timestamp;
            }
        })
        .catch(error => console.error('Error fetching dashboard metrics:', error));
    }

    function loadActivityFeed() {
        fetch('/api/user/recent-activities', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('activity-feed-container');
            if (!container) return;

            if (!data.activities || data.activities.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 32px 20px; color: #9ca3af;"><p style="font-size: 14px; margin: 0;">📭 No recent activities yet</p></div>';
                return;
            }

            const activitiesHtml = data.activities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon">${activity.icon}</div>
                    <div class="activity-content">
                        <div class="title">${activity.user_name}</div>
                        <p class="description">${activity.description}</p>
                        <div class="time">${activity.time_ago}</div>
                    </div>
                </div>
            `).join('');

            container.innerHTML = activitiesHtml;
        })
        .catch(error => console.error('Error fetching activities:', error));
    }

    window.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        setDateRange('today');
        loadActivityFeed();
    });

    setInterval(updateDashboardMetrics, REFRESH_INTERVAL);
    setInterval(loadActivityFeed, REFRESH_INTERVAL);
</script>
@endsection
