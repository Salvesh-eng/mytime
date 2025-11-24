@extends('layouts.app')

@section('title', 'Analytics & Insights')
@section('page-title', 'Analytics & Insights')

@section('styles')
<style>
    /* Analytics Page Styles */
    .analytics-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
    }

    .analytics-header h1 {
        margin: 0 0 8px 0;
        font-size: 32px;
        font-weight: 700;
    }

    .analytics-header p {
        margin: 0 0 20px 0;
        font-size: 14px;
        opacity: 0.9;
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
        background: rgba(255, 255, 255, 0.15);
        padding: 8px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .range-btn {
        padding: 8px 14px;
        border: none;
        background: transparent;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .range-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .range-btn.active {
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .header-actions {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }

    .btn-control {
        padding: 10px 16px;
        border: none;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-control:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .auto-refresh-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        font-size: 13px;
    }

    .toggle-switch {
        width: 44px;
        height: 24px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 12px;
        cursor: pointer;
        position: relative;
        transition: all 0.3s;
    }

    .toggle-switch.active {
        background: rgba(255, 255, 255, 0.4);
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
    }

    .toggle-switch.active::after {
        left: 22px;
    }

    .last-updated {
        font-size: 12px;
        opacity: 0.8;
        margin-top: 8px;
    }

    /* Executive Summary Grid */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border-top: 4px solid;
    }

    .summary-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .summary-card.blue {
        border-top-color: #2563EB;
        background: linear-gradient(135deg, #2563EB08 0%, #2563EB04 100%);
    }

    .summary-card.green {
        border-top-color: #16A34A;
        background: linear-gradient(135deg, #16A34A08 0%, #16A34A04 100%);
    }

    .summary-card.purple {
        border-top-color: #9333EA;
        background: linear-gradient(135deg, #9333EA08 0%, #9333EA04 100%);
    }

    .summary-card.orange {
        border-top-color: #F59E0B;
        background: linear-gradient(135deg, #F59E0B08 0%, #F59E0B04 100%);
    }

    .summary-card.teal {
        border-top-color: #06B6D4;
        background: linear-gradient(135deg, #06B6D408 0%, #06B6D404 100%);
    }

    .summary-card.gold {
        border-top-color: #D97706;
        background: linear-gradient(135deg, #D9770608 0%, #D9770604 100%);
    }

    .card-content {
        position: relative;
        z-index: 1;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .card-label {
        font-size: 14px;
        color: #6B7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-icon {
        font-size: 24px;
    }

    .card-metric {
        font-size: 32px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 12px;
        line-height: 1;
    }

    .card-comparison {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .comparison-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .comparison-badge.positive {
        background: #DCFCE7;
        color: #15803D;
    }

    .comparison-badge.negative {
        background: #FEE2E2;
        color: #991B1B;
    }

    .card-description {
        font-size: 12px;
        color: #9CA3AF;
    }



    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .status-on-track {
        background: #DCFCE7;
        color: #15803D;
    }

    .status-at-risk {
        background: #FEE2E2;
        color: #991B1B;
    }

    .status-ahead {
        background: #BFDBFE;
        color: #1E40AF;
    }

    /* Bar Chart */
    .bar-chart-container {
        display: flex;
        gap: 8px;
        padding: 16px 0;
    }

    .custom-range-picker {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .header-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .range-buttons {
            width: 100%;
            justify-content: space-between;
        }

        .range-btn {
            flex: 1;
        }

        .header-actions {
            width: 100%;
            margin-left: 0;
        }

        .card-metric {
            font-size: 24px;
        }
    }

    /* Loading State */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 2s infinite;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Analytics Header -->
<div class="analytics-header">
    <h1>📊 Analytics & Insights</h1>
    <p>Comprehensive analysis of time tracking, productivity, and resource utilization</p>

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
            <input type="date" id="start-date" value="{{ $startDate->format('Y-m-d') }}" style="padding: 8px 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; background: rgba(255,255,255,0.1); color: white;">
            <input type="date" id="end-date" value="{{ $endDate->format('Y-m-d') }}" style="padding: 8px 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; background: rgba(255,255,255,0.1); color: white;">
            <button onclick="applyCustomRange()" class="btn-control">Apply</button>
        </div>

        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Export Dropdown -->
            <div style="position: relative;">
                <button class="btn-control" onclick="toggleExportMenu()">📥 Export</button>
                <div id="export-menu" style="position: absolute; top: 100%; right: 0; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 150px; z-index: 100; display: none; margin-top: 8px; overflow: hidden;">
                    <a href="{{ route('admin.analytics.export', ['format' => 'csv', 'range' => $dateRange]) }}" style="display: block; padding: 12px 16px; color: #0F172A; text-decoration: none; border-bottom: 1px solid #e5e7eb; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor=''">
                        📄 CSV
                    </a>
                    <a href="{{ route('admin.analytics.export', ['format' => 'xlsx', 'range' => $dateRange]) }}" style="display: block; padding: 12px 16px; color: #0F172A; text-decoration: none; border-bottom: 1px solid #e5e7eb; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor=''">
                        📊 Excel
                    </a>
                    <a href="{{ route('admin.analytics.export', ['format' => 'pdf', 'range' => $dateRange]) }}" style="display: block; padding: 12px 16px; color: #0F172A; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor=''">
                        📑 PDF
                    </a>
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
        ⏰ Last updated: <span id="last-updated-time">Just now</span>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 24px; margin-bottom: 40px;">
    <!-- Hours Tracked Over Time Chart -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #0F172A;">📈 Hours Tracked Over Time</h3>
        <canvas id="hoursChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Project Status Distribution Chart -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #0F172A;">📊 Project Status Distribution</h3>
        <canvas id="projectStatusChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Team Hours Distribution Chart -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #0F172A;">👥 Team Hours Distribution</h3>
        <canvas id="teamHoursChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Billable vs Non-Billable Chart -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #0F172A;">💰 Billable vs Non-Billable</h3>
        <canvas id="billableChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Daily Average Hours Chart -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #0F172A;">📅 Daily Average Hours</h3>
        <canvas id="dailyAverageChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Project Progress Chart -->
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #0F172A;">🎯 Project Progress</h3>
        <canvas id="projectProgressChart" style="max-height: 300px;"></canvas>
    </div>
</div>

<!-- Executive Summary Cards -->
<div class="summary-grid">
    <!-- Card 1: Total Hours Tracked -->
    <div class="summary-card blue">
        <div class="card-content">
            <div class="card-header">
                <div class="card-label">Total Hours Tracked</div>
                <div class="card-icon">⏱️</div>
            </div>
            <div class="card-metric">{{ number_format($currentData['total_hours'], 1) }}h</div>
            <div class="card-comparison">
                <span class="comparison-badge @if($comparisons['hours_change'] >= 0) positive @else negative @endif">
                    @if($comparisons['hours_change'] >= 0) ↑ @else ↓ @endif {{ abs($comparisons['hours_change']) }}%
                </span>
                <span class="card-description">vs previous period</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Billable Hours -->
    <div class="summary-card green">
        <div class="card-content">
            <div class="card-header">
                <div class="card-label">Billable Hours</div>
                <div class="card-icon">💰</div>
            </div>
            <div class="card-metric">{{ number_format($currentData['billable_hours'], 1) }}h</div>
            <div style="font-size: 13px; color: #6B7280; margin-bottom: 16px;">
                <span style="font-weight: 600; color: #0F172A;">{{ $currentData['billable_percentage'] }}%</span> of total
            </div>
            <div class="card-comparison" style="margin-top: 16px;">
                <span class="comparison-badge @if($comparisons['billable_change'] >= 0) positive @else negative @endif">
                    @if($comparisons['billable_change'] >= 0) ↑ @else ↓ @endif {{ abs($comparisons['billable_change']) }}%
                </span>
            </div>
        </div>
    </div>

    <!-- Card 3: Projects in Progress -->
    <div class="summary-card purple">
        <div class="card-content">
            <div class="card-header">
                <div class="card-label">Projects in Progress</div>
                <div class="card-icon">📁</div>
            </div>
            <div class="card-metric">{{ $projectStatus['total'] }}</div>
            <div style="margin-bottom: 16px;">
                @if($projectStatus['at_risk'] > 0)
                    <span class="status-badge status-at-risk">⚠️ {{ $projectStatus['at_risk'] }} At Risk</span>
                @endif
                @if($projectStatus['on_track'] > 0)
                    <span class="status-badge status-on-track">✓ {{ $projectStatus['on_track'] }} On Track</span>
                @endif
                @if($projectStatus['ahead'] > 0)
                    <span class="status-badge status-ahead">🚀 {{ $projectStatus['ahead'] }} Ahead</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Card 4: Team Utilization Rate -->
    <div class="summary-card orange">
        <div class="card-content">
            <div class="card-header">
                <div class="card-label">Team Utilization Rate</div>
                <div class="card-icon">👥</div>
            </div>
            <div class="card-metric">{{ $currentData['utilization_rate'] }}%</div>
            <div style="font-size: 12px; color: #6B7280; margin-bottom: 16px;">Target: 85%</div>
            <div class="gauge-container">
                <div style="font-size: 24px; font-weight: 700; color: #0F172A;">{{ $currentData['utilization_rate'] }}%</div>
            </div>
            <div class="card-comparison" style="margin-top: 16px;">
                <span class="comparison-badge @if($comparisons['utilization_change'] >= 0) positive @else negative @endif">
                    @if($comparisons['utilization_change'] >= 0) ↑ @else ↓ @endif {{ abs($comparisons['utilization_change']) }}%
                </span>
            </div>
        </div>
    </div>

    <!-- Card 5: Average Hours per Day -->
    <div class="summary-card teal">
        <div class="card-content">
            <div class="card-header">
                <div class="card-label">Average Hours per Day</div>
                <div class="card-icon">📈</div>
            </div>
            <div class="card-metric">{{ number_format($currentData['average_hours_per_day'], 1) }}h</div>
            <div style="font-size: 12px; color: #6B7280; margin-bottom: 16px;">
                @if($currentData['average_hours_per_day'] > 8)
                    <span style="color: #F59E0B;">Above standard workday</span>
                @elseif($currentData['average_hours_per_day'] < 7)
                    <span style="color: #06B6D4;">Below standard workday</span>
                @else
                    <span style="color: #16A34A;">Standard workday</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Card 6: Revenue Generated -->
    <div class="summary-card gold">
        <div class="card-content">
            <div class="card-header">
                <div class="card-label">Revenue Generated</div>
                <div class="card-icon">💵</div>
            </div>
            <div class="card-metric">${{ number_format($currentData['revenue'], 0) }}</div>
            <div style="font-size: 12px; color: #6B7280; margin-bottom: 16px;">
                Based on billable hours @ $50/hour
            </div>
            <div class="card-comparison">
                <span class="comparison-badge @if($comparisons['revenue_change'] >= 0) positive @else negative @endif">
                    @if($comparisons['revenue_change'] >= 0) ↑ @else ↓ @endif {{ abs($comparisons['revenue_change']) }}%
                </span>
            </div>
        </div>
    </div>
</div>

<script>
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
        const params = new URLSearchParams(window.location.search);
        fetch(`{{ route('admin.analytics.getMetrics') }}?${params.toString()}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('last-updated-time').textContent = data.timestamp;
                console.log('Analytics refreshed:', data);
            })
            .catch(e => console.error('Refresh error:', e));
    }

    // Auto-refresh Toggle
    function toggleAutoRefresh() {
        const toggle = document.getElementById('auto-refresh-toggle');
        toggle.classList.toggle('active');

        if (toggle.classList.contains('active')) {
            autoRefreshInterval = setInterval(refreshAnalytics, 5 * 60 * 1000); // 5 minutes
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

    // Update time every minute
    setInterval(updateLastUpdatedTime, 60000);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateLastUpdatedTime();
        initializeCharts();
    });

    let autoRefreshInterval = null;

    // Initialize Charts
    function initializeCharts() {
        // Hours Tracked Over Time Chart
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        new Chart(hoursCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Hours Tracked',
                    data: [8.5, 9.2, 7.8, 8.9, 9.1, 4.2, 2.1],
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
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
                        max: 12,
                        ticks: { color: '#6B7280' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#6B7280' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Project Status Distribution Chart
        const statusCtx = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Planning', 'In Progress', 'Completed', 'On Hold'],
                datasets: [{
                    data: [12, 28, 15, 5],
                    backgroundColor: ['#F59E0B', '#06B6D4', '#16A34A', '#6B7280'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15 }
                    }
                }
            }
        });

        // Team Hours Distribution Chart
        const teamCtx = document.getElementById('teamHoursChart').getContext('2d');
        new Chart(teamCtx, {
            type: 'bar',
            data: {
                labels: ['John', 'Sarah', 'Mike', 'Emma', 'David'],
                datasets: [{
                    label: 'Hours Logged',
                    data: [42, 38, 45, 35, 40],
                    backgroundColor: ['#2563EB', '#06B6D4', '#16A34A', '#F59E0B', '#9333EA'],
                    borderRadius: 6,
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
                        grid: { color: '#e5e7eb' }
                    },
                    y: {
                        ticks: { color: '#6B7280' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Billable vs Non-Billable Chart
        const billableCtx = document.getElementById('billableChart').getContext('2d');
        new Chart(billableCtx, {
            type: 'pie',
            data: {
                labels: ['Billable', 'Non-Billable'],
                datasets: [{
                    data: [72, 28],
                    backgroundColor: ['#16A34A', '#E5E7EB'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#6B7280', padding: 15 }
                    }
                }
            }
        });

        // Daily Average Hours Chart
        const dailyCtx = document.getElementById('dailyAverageChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Average Hours',
                    data: [8.2, 8.5, 8.1, 8.8],
                    backgroundColor: '#06B6D4',
                    borderRadius: 6,
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
                        max: 10,
                        ticks: { color: '#6B7280' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#6B7280' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Project Progress Chart
        const progressCtx = document.getElementById('projectProgressChart').getContext('2d');
        new Chart(progressCtx, {
            type: 'bar',
            data: {
                labels: ['Project A', 'Project B', 'Project C', 'Project D', 'Project E'],
                datasets: [{
                    label: 'Progress %',
                    data: [85, 60, 45, 90, 70],
                    backgroundColor: ['#16A34A', '#F59E0B', '#06B6D4', '#16A34A', '#9333EA'],
                    borderRadius: 6,
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
                        ticks: { color: '#6B7280' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#6B7280' },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endsection
