@extends('layouts.app')

@section('title', 'Analytics & Insights')
@section('page-title', 'Analytics & Insights')

@section('styles')
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
    }

    /* Analytics Page Styles */
    .analytics-header {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.25) 0%, rgba(217, 179, 255, 0.25) 50%, rgba(179, 255, 217, 0.2) 100%);
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        border: 1px solid rgba(179, 217, 255, 0.3);
        box-shadow: 0 8px 32px rgba(179, 217, 255, 0.15);
    }

    .analytics-header h1 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
    }

    .analytics-header p {
        margin: 0 0 20px 0;
        font-size: 14px;
        color: #6B7280;
    }

    .header-controls {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .date-range-selector {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .range-buttons {
        display: flex;
        gap: 8px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.3) 0%, rgba(217, 179, 255, 0.3) 100%);
        padding: 8px;
        border-radius: 12px;
    }

    .range-btn {
        padding: 8px 14px;
        border: none;
        background: transparent;
        color: #0c4a6e;
        border-radius: 8px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .range-btn:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    .range-btn.active {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: #0c4a6e;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }

    .btn-control {
        padding: 10px 16px;
        border: none;
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        color: #0c4a6e;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-control:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(179, 217, 255, 0.3);
    }

    .auto-refresh-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #0c4a6e;
        font-size: 13px;
    }

    .toggle-switch {
        width: 44px;
        height: 24px;
        background: rgba(179, 217, 255, 0.4);
        border: none;
        border-radius: 12px;
        cursor: pointer;
        position: relative;
        transition: all 0.3s;
    }

    .toggle-switch.active {
        background: var(--soft-green);
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 10px;
        top: 2px;
        left: 2px;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .toggle-switch.active::after {
        left: 22px;
    }

    .last-updated {
        font-size: 12px;
        color: #6B7280;
        margin-top: 12px;
    }

    .custom-range-picker {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .custom-range-picker input[type="date"] {
        padding: 8px 12px;
        border: 1.5px solid rgba(179, 217, 255, 0.4);
        border-radius: 8px;
        background: white;
        color: #0F172A;
        font-size: 13px;
    }

    .custom-range-picker input[type="date"]:focus {
        outline: none;
        border-color: var(--soft-blue);
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .chart-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 217, 255, 0.08) 100%);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.1);
        border: 1px solid rgba(179, 217, 255, 0.2);
        transition: all 0.3s;
    }

    .chart-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 32px rgba(179, 217, 255, 0.2);
    }

    .chart-card h3 {
        margin: 0 0 20px 0;
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-icon {
        padding: 8px;
        border-radius: 10px;
        font-size: 16px;
    }

    .chart-icon.blue { background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%); }
    .chart-icon.green { background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); }
    .chart-icon.purple { background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); }
    .chart-icon.orange { background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); }
    .chart-icon.pink { background: linear-gradient(135deg, var(--soft-pink) 0%, #ffcce6 100%); }

    /* Section Title */
    .section-title {
        margin: 40px 0 24px 0;
        font-size: 20px;
        font-weight: 700;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title-icon {
        padding: 10px;
        border-radius: 12px;
        font-size: 20px;
    }

    /* Export Menu */
    .export-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        min-width: 150px;
        z-index: 100;
        display: none;
        margin-top: 8px;
        overflow: hidden;
        border: 1px solid rgba(179, 217, 255, 0.3);
    }

    .export-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        color: #0F172A;
        text-decoration: none;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s;
        font-size: 13px;
        font-weight: 500;
    }

    .export-menu a:last-child {
        border-bottom: none;
    }

    .export-menu a:hover {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.15) 0%, rgba(217, 179, 255, 0.15) 100%);
    }

    /* No Data State */
    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #9CA3AF;
    }

    .no-data-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .header-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .range-buttons {
            width: 100%;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .range-btn {
            flex: 1;
            min-width: auto;
        }

        .header-actions {
            width: 100%;
            margin-left: 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Analytics Header -->
<div class="analytics-header">
    <h1>📊 Analytics & Insights</h1>
    <p>Real-time analysis of projects, productivity, and financial performance</p>

    <div class="header-controls">
        <!-- Date Range Selector -->
        <div class="date-range-selector">
            <div class="range-buttons">
                <button class="range-btn @if($dateRange === 'today') active @endif" onclick="selectRange('today')">Today</button>
                <button class="range-btn @if($dateRange === 'yesterday') active @endif" onclick="selectRange('yesterday')">Yesterday</button>
                <button class="range-btn @if($dateRange === '7days') active @endif" onclick="selectRange('7days')">7 Days</button>
                <button class="range-btn @if($dateRange === '30days') active @endif" onclick="selectRange('30days')">30 Days</button>
                <button class="range-btn @if($dateRange === 'month') active @endif" onclick="selectRange('month')">This Month</button>
                <button class="range-btn @if($dateRange === 'last_month') active @endif" onclick="selectRange('last_month')">Last Month</button>
                <button class="range-btn @if($dateRange === 'custom') active @endif" onclick="toggleCustomRange()">Custom</button>
            </div>
        </div>

        <!-- Custom Date Range (Hidden by default) -->
        <div id="custom-range-picker" class="custom-range-picker" @if($dateRange !== 'custom') style="display: none;" @endif>
            <input type="date" id="start-date" value="{{ $startDate->format('Y-m-d') }}">
            <input type="date" id="end-date" value="{{ $endDate->format('Y-m-d') }}">
            <button onclick="applyCustomRange()" class="btn-control">Apply</button>
        </div>

        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Export Dropdown -->
            <div style="position: relative;">
                <button class="btn-control" onclick="toggleExportMenu()">📥 Export</button>
                <div id="export-menu" class="export-menu">
                    <a href="{{ route('admin.analytics.export', ['format' => 'csv', 'range' => $dateRange]) }}">📄 CSV</a>
                    <a href="{{ route('admin.analytics.export', ['format' => 'xlsx', 'range' => $dateRange]) }}">📊 Excel</a>
                    <a href="{{ route('admin.analytics.export', ['format' => 'pdf', 'range' => $dateRange]) }}">📑 PDF</a>
                </div>
            </div>

            <!-- Refresh Button -->
            <button class="btn-control" onclick="refreshAnalytics()">🔄 Refresh</button>

            <!-- Auto-refresh Toggle -->
            <div class="auto-refresh-toggle">
                <button class="toggle-switch" id="auto-refresh-toggle" onclick="toggleAutoRefresh()"></button>
                <span>Auto-refresh</span>
            </div>
        </div>
    </div>

    <div class="last-updated">
        ⏰ Last updated: <span id="last-updated-time">Just now</span> | 
        📅 Showing data from {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}
    </div>
</div>

<!-- Project Analytics Charts -->
<div class="section-title">
    <span class="section-title-icon" style="background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);">📁</span>
    Project Analytics
</div>

<div class="charts-grid">
    <!-- Weekly Productivity Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon blue">📈</span> Weekly Productivity Trend</h3>
        <canvas id="hoursChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Project Status Distribution Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon purple">📊</span> Project Status Distribution</h3>
        <canvas id="projectStatusChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Team Performance Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon green">👥</span> Team Performance</h3>
        <canvas id="teamHoursChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Project Progress Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon orange">🎯</span> Project Completion Progress</h3>
        <canvas id="projectProgressChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Task Completion Rate Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon pink">✅</span> Task Completion Rate</h3>
        <canvas id="taskCompletionChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Monthly Activity Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon blue">📅</span> Monthly Activity Overview</h3>
        <canvas id="dailyAverageChart" style="max-height: 280px;"></canvas>
    </div>
</div>

<!-- Financial Analytics Charts -->
<div class="section-title">
    <span class="section-title-icon" style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);">💰</span>
    Financial Analytics
</div>

<div class="charts-grid">
    <!-- Monthly Income vs Expenses Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon green">📊</span> Monthly Income vs Expenses</h3>
        <canvas id="monthlyFinancialChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Spending by Category Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon purple">🏷️</span> Spending by Category</h3>
        <canvas id="transactionsCategoryChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Budget Utilization Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon blue">💼</span> Budget Utilization</h3>
        <canvas id="budgetUtilizationChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Income Sources Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon orange">💵</span> Income Sources</h3>
        <canvas id="incomeSourcesChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Net Profit Trend Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon green">💹</span> Net Profit Trend</h3>
        <canvas id="profitMarginChart" style="max-height: 280px;"></canvas>
    </div>

    <!-- Savings Rate Chart -->
    <div class="chart-card">
        <h3><span class="chart-icon pink">🏦</span> Savings & Investment</h3>
        <canvas id="savingsChart" style="max-height: 280px;"></canvas>
    </div>
</div>

<script>
    // Data from database
    const weeklyProductivity = @json($weeklyProductivity);
    const projectStatusData = @json($projectStatus);
    const teamPerformance = @json($teamPerformance);
    const projectProgress = @json($projectProgress);
    const taskCompletion = @json($taskCompletion);
    const monthlyActivity = @json($monthlyActivity);
    const monthlyFinancialData = @json($monthlyFinancialData);
    const transactionsByCategory = @json($transactionsByCategory);
    const financialData = @json($financialData);
    const incomeSources = @json($incomeSources);
    const netProfitTrend = @json($netProfitTrend);
    const savingsData = @json($savingsData);

    // Date Range Selection
    function selectRange(range) {
        const params = new URLSearchParams();
        params.set('range', range);
        window.location.href = `{{ route('admin.analytics.index') }}?${params.toString()}`;
    }

    // Custom Range Toggle
    function toggleCustomRange() {
        const picker = document.getElementById('custom-range-picker');
        picker.style.display = picker.style.display === 'none' ? 'flex' : 'none';
    }

    // Apply Custom Range
    function applyCustomRange() {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        const params = new URLSearchParams();
        params.set('range', 'custom');
        params.set('start_date', startDate);
        params.set('end_date', endDate);
        window.location.href = `{{ route('admin.analytics.index') }}?${params.toString()}`;
    }

    // Export Menu Toggle
    function toggleExportMenu() {
        const menu = document.getElementById('export-menu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    // Close export menu on click outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('export-menu');
        if (!event.target.closest('.header-actions')) {
            menu.style.display = 'none';
        }
    });

    // Refresh Analytics
    function refreshAnalytics() {
        window.location.reload();
    }

    // Auto-refresh Toggle
    let autoRefreshInterval = null;
    function toggleAutoRefresh() {
        const toggle = document.getElementById('auto-refresh-toggle');
        toggle.classList.toggle('active');

        if (toggle.classList.contains('active')) {
            autoRefreshInterval = setInterval(() => window.location.reload(), 5 * 60 * 1000);
        } else {
            clearInterval(autoRefreshInterval);
        }
    }

    // Update last updated time
    function updateLastUpdatedTime() {
        const lastUpdated = document.getElementById('last-updated-time');
        const now = new Date();
        lastUpdated.textContent = now.toLocaleTimeString();
    }

    setInterval(updateLastUpdatedTime, 60000);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateLastUpdatedTime();
        initializeCharts();
    });

    // Initialize Charts with Real Database Data
    function initializeCharts() {
        // Weekly Productivity Chart
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        new Chart(hoursCtx, {
            type: 'line',
            data: {
                labels: weeklyProductivity.labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Activity Count',
                    data: weeklyProductivity.values || [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(179, 217, 255, 0.3)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#2563EB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6B7280' },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Project Status Distribution Chart
        const planning = {{ $projectStatus['at_risk'] ?? 0 }};
        const inProgress = {{ $projectStatus['on_track'] ?? 0 }};
        const completed = {{ $projectStatus['ahead'] ?? 0 }};
        const onHold = {{ $projectStatus['total'] ?? 0 }} - planning - inProgress - completed;
        
        const statusCtx = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Planning', 'In Progress', 'Completed', 'On Hold'],
                datasets: [{
                    data: [planning, inProgress, completed, Math.max(0, onHold)],
                    backgroundColor: ['#FFD9B3', '#B3D9FF', '#B3FFB3', '#E5E7EB'],
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15, font: { weight: 600 } }
                    }
                }
            }
        });

        // Team Performance Chart
        const teamCtx = document.getElementById('teamHoursChart').getContext('2d');
        const teamLabels = teamPerformance.labels || ['No team data'];
        const teamValues = teamPerformance.values || [0];
        const teamColors = ['#B3D9FF', '#D9B3FF', '#B3FFB3', '#FFD9B3', '#FFB3D9'];
        
        new Chart(teamCtx, {
            type: 'bar',
            data: {
                labels: teamLabels,
                datasets: [{
                    label: 'Tasks/Projects',
                    data: teamValues,
                    backgroundColor: teamLabels.map((_, i) => teamColors[i % teamColors.length]),
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: '#6B7280' },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    y: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Project Progress Chart
        const progressCtx = document.getElementById('projectProgressChart').getContext('2d');
        const progressLabels = projectProgress.labels || ['No projects'];
        const progressValues = projectProgress.values || [0];
        const progressColors = progressValues.map(v => {
            if (v >= 80) return '#B3FFB3';
            if (v >= 50) return '#B3D9FF';
            if (v >= 25) return '#FFD9B3';
            return '#FFB3D9';
        });
        
        new Chart(progressCtx, {
            type: 'bar',
            data: {
                labels: progressLabels,
                datasets: [{
                    label: 'Progress %',
                    data: progressValues,
                    backgroundColor: progressColors,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#6B7280', callback: value => value + '%' },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Task Completion Rate Chart
        const taskCompletionCtx = document.getElementById('taskCompletionChart').getContext('2d');
        new Chart(taskCompletionCtx, {
            type: 'doughnut',
            data: {
                labels: taskCompletion.labels || ['Completed', 'In Progress', 'Pending'],
                datasets: [{
                    data: taskCompletion.values || [0, 0, 0],
                    backgroundColor: ['#B3FFB3', '#B3D9FF', '#FFD9B3'],
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15, font: { weight: 600 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const counts = taskCompletion.counts || [0, 0, 0];
                                return context.label + ': ' + context.parsed + '% (' + counts[context.dataIndex] + ' projects)';
                            }
                        }
                    }
                }
            }
        });

        // Monthly Activity Chart
        const dailyCtx = document.getElementById('dailyAverageChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: monthlyActivity.labels || [],
                datasets: [{
                    label: 'Projects Completed',
                    data: monthlyActivity.values || [],
                    backgroundColor: 'rgba(179, 217, 255, 0.8)',
                    borderColor: '#2563EB',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6B7280', stepSize: 1 },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Monthly Income vs Expenses Chart
        const monthlyFinancialCtx = document.getElementById('monthlyFinancialChart').getContext('2d');
        new Chart(monthlyFinancialCtx, {
            type: 'bar',
            data: {
                labels: monthlyFinancialData.months || [],
                datasets: [
                    {
                        label: 'Income',
                        data: monthlyFinancialData.income || [],
                        backgroundColor: 'rgba(179, 255, 179, 0.8)',
                        borderColor: '#16A34A',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    },
                    {
                        label: 'Expenses',
                        data: monthlyFinancialData.expenses || [],
                        backgroundColor: 'rgba(255, 179, 217, 0.8)',
                        borderColor: '#DC2626',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15, font: { weight: 600 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6B7280', callback: value => '$' + value.toLocaleString() },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Spending by Category Chart
        const categoryLabels = transactionsByCategory.map(t => t.category) || ['No data'];
        const categoryAmounts = transactionsByCategory.map(t => t.amount) || [0];
        const categoryCtx = document.getElementById('transactionsCategoryChart').getContext('2d');
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels.length > 0 ? categoryLabels : ['No expenses'],
                datasets: [{
                    data: categoryAmounts.length > 0 ? categoryAmounts : [1],
                    backgroundColor: ['#B3D9FF', '#D9B3FF', '#B3FFB3', '#FFD9B3', '#FFB3D9', '#E6D9FF', '#B3FFD9', '#FFCCB3'],
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 12, font: { weight: 600 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Budget Utilization Chart
        const budgetUtilizationCtx = document.getElementById('budgetUtilizationChart').getContext('2d');
        const totalBudget = financialData.total_budget || 0;
        const budgetSpent = financialData.budget_spent || 0;
        const budgetPercentage = totalBudget > 0 ? Math.round((budgetSpent / totalBudget) * 100) : 0;
        
        new Chart(budgetUtilizationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Spent ($' + budgetSpent.toLocaleString() + ')', 'Remaining ($' + (totalBudget - budgetSpent).toLocaleString() + ')'],
                datasets: [{
                    data: [budgetPercentage, 100 - budgetPercentage],
                    backgroundColor: ['#B3D9FF', '#E5E7EB'],
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15, font: { weight: 600 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ' (' + context.parsed.toFixed(1) + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Income Sources Chart
        const incomeSourcesCtx = document.getElementById('incomeSourcesChart').getContext('2d');
        const incomeLabels = incomeSources.labels || ['No income data'];
        const incomeValues = incomeSources.values || [0];
        
        new Chart(incomeSourcesCtx, {
            type: 'bar',
            data: {
                labels: incomeLabels,
                datasets: [{
                    label: 'Amount ($)',
                    data: incomeValues,
                    backgroundColor: ['#B3FFB3', '#D9B3FF', '#B3D9FF', '#FFD9B3', '#FFB3D9'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6B7280', callback: value => '$' + value.toLocaleString() },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Net Profit Trend Chart
        const profitMarginCtx = document.getElementById('profitMarginChart').getContext('2d');
        new Chart(profitMarginCtx, {
            type: 'line',
            data: {
                labels: netProfitTrend.labels || [],
                datasets: [{
                    label: 'Net Profit',
                    data: netProfitTrend.values || [],
                    borderColor: '#16A34A',
                    backgroundColor: 'rgba(179, 255, 179, 0.3)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        return value >= 0 ? '#16A34A' : '#DC2626';
                    },
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: { color: '#6B7280', callback: value => '$' + value.toLocaleString() },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // Savings & Investment Chart
        const savingsCtx = document.getElementById('savingsChart').getContext('2d');
        new Chart(savingsCtx, {
            type: 'bar',
            data: {
                labels: savingsData.labels || [],
                datasets: [
                    {
                        label: 'Savings',
                        data: savingsData.savings || [],
                        backgroundColor: 'rgba(179, 217, 255, 0.8)',
                        borderColor: '#2563EB',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    },
                    {
                        label: 'Investments',
                        data: savingsData.investments || [],
                        backgroundColor: 'rgba(217, 179, 255, 0.8)',
                        borderColor: '#9333EA',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15, font: { weight: 600 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6B7280', callback: value => '$' + value.toLocaleString() },
                        grid: { color: 'rgba(179, 217, 255, 0.2)' }
                    },
                    x: {
                        ticks: { color: '#6B7280', font: { weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endsection
