@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

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

    /* Chart Container Styles */
    .chart-widget {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 24px;
        margin-bottom: 32px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .chart-header h2 {
        margin: 0;
        font-size: 20px;
        color: #0F172A;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-header .month-label {
        font-size: 14px;
        color: #6B7280;
        background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        border: 1px solid #cbd5e1;
    }

    .chart-wrapper {
        position: relative;
        height: 400px;
        margin-bottom: 16px;
    }

    .chart-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .chart-stat-item {
        text-align: center;
        padding: 12px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .chart-stat-label {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .chart-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #2563EB;
    }

<style>
    .progress-bar-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .progress-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
        border-left: 4px solid #2563EB;
    }

    .progress-card:hover {
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);
        transform: translateY(-2px);
    }

    .progress-card h3 {
        margin: 0 0 10px 0;
        font-size: 16px;
        color: #0F172A;
        font-weight: 600;
    }

    .tag {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        margin-right: 6px;
        margin-bottom: 8px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 12px;
        color: #6B7280;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 12px;
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

    .progress-fill.red {
        background: linear-gradient(90deg, #DC2626, #b91c1c);
    }

    .card-footer {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6B7280;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .filter-section .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .filter-section .form-group {
        margin-bottom: 0;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .action-buttons a, .action-buttons button {
        flex: 1;
        padding: 10px 12px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-box {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        border-left: 4px solid #2563EB;
    }

    .stat-box h4 {
        margin: 0;
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-box .value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
    }
    
    /* Project card status colors */
    .project-card.planning {
        border-left: 4px solid #F59E0B;
    }
    
    .project-card.in-progress {
        border-left: 4px solid #06B6D4;
    }
    
    .project-card.completed {
        border-left: 4px solid #16A34A;
    }
    
    .project-card.on-hold {
        border-left: 4px solid #6B7280;
    }
    
    /* Default color if status is not recognized */
    .project-card.default {
        border-left: 4px solid #2563EB;
    }
</style>

    <!-- Header Section -->
    <div style="margin-bottom: 32px; background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(255, 179, 217, 0.15) 100%); border-radius: 20px; padding: 24px 28px; border: 1px solid rgba(179, 255, 179, 0.3); box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 26px; color: #0F172A; font-weight: 700; margin-bottom: 6px; letter-spacing: -0.5px;">👋 Welcome to Dashboard</h1>
                <p style="color: #6B7280; font-size: 14px; margin: 0;">Here's what's happening with your projects and team</p>
            </div>
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <a href="/add-time" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); color: #15803d; text-decoration: none; border-radius: 12px; font-size: 14px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 12px rgba(179, 255, 179, 0.3); border: 1px solid rgba(179, 255, 179, 0.5);">▶ Quick Timer</a>
                <div style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.3) 0%, rgba(230, 217, 255, 0.3) 100%); padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(179, 217, 255, 0.4); text-align: right;">
                    <div style="font-size: 11px; color: #6B7280; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Last updated</div>
                    <div style="font-size: 14px; font-weight: 600; color: #0c4a6e;" id="last-updated-time">Just now</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 8px; margin-bottom: 16px;">
        <!-- Total Users Card -->
        <div style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.3) 0%, rgba(179, 217, 255, 0.1) 100%); border-radius: 8px; border: 1px solid var(--soft-blue); padding: 8px; box-shadow: 0 1px 2px rgba(179, 217, 255, 0.2);">
            <h3 style="color: #0c4a6e; font-size: 9px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.2px;">👥 Users</h3>
            <div style="font-size: 18px; font-weight: 700; color: var(--soft-blue);" id="metric-users">{{ $totalUsers }}</div>
            <div style="font-size: 8px; color: var(--soft-blue); margin-top: 2px; font-weight: 600;">Active</div>
        </div>

        <!-- Hours Logged Today Card -->
        <div style="background: linear-gradient(135deg, rgba(179, 255, 179, 0.3) 0%, rgba(179, 255, 179, 0.1) 100%); border-radius: 8px; border: 1px solid var(--soft-green); padding: 8px; box-shadow: 0 1px 2px rgba(179, 255, 179, 0.2);">
            <h3 style="color: #15803d; font-size: 9px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.2px;">⏱️ Hours</h3>
            <div style="font-size: 18px; font-weight: 700; color: var(--soft-green);" id="metric-hours">{{ number_format($hoursLoggedToday, 2) }}</div>
            <div style="font-size: 8px; color: var(--soft-green); margin-top: 2px; font-weight: 600;">Today</div>
        </div>

        <!-- Pending Approvals Card -->
        <div style="background: linear-gradient(135deg, rgba(255, 217, 179, 0.3) 0%, rgba(255, 217, 179, 0.1) 100%); border-radius: 8px; border: 1px solid var(--soft-orange); padding: 8px; box-shadow: 0 1px 2px rgba(255, 217, 179, 0.2);">
            <h3 style="color: #92400e; font-size: 9px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.2px;">⏳ Pending</h3>
            <div style="font-size: 18px; font-weight: 700; color: var(--soft-orange);" id="metric-pending">{{ $pendingApprovals }}</div>
            <div style="font-size: 8px; color: var(--soft-orange); margin-top: 2px; font-weight: 600;">Reviews</div>
        </div>

        <!-- Total Projects Card -->
        <div style="background: linear-gradient(135deg, rgba(217, 179, 255, 0.3) 0%, rgba(217, 179, 255, 0.1) 100%); border-radius: 8px; border: 1px solid var(--soft-purple); padding: 8px; box-shadow: 0 1px 2px rgba(217, 179, 255, 0.2);">
            <h3 style="color: #6b21a8; font-size: 9px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.2px;">📁 Projects</h3>
            <div style="font-size: 18px; font-weight: 700; color: var(--soft-purple);" id="metric-projects">{{ $totalProjects }}</div>
            <div style="font-size: 8px; color: var(--soft-purple); margin-top: 2px; font-weight: 600;">Active</div>
        </div>
    </div>

    <style>
        /* Dashboard Chart Cards */
        .dashboard-chart-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(179, 255, 179, 0.08) 100%);
            border-radius: 16px;
            border: 1px solid rgba(179, 255, 179, 0.3);
            box-shadow: 0 4px 16px rgba(179, 255, 179, 0.12);
            padding: 20px;
            transition: all 0.3s ease;
        }

        .dashboard-chart-card:hover {
            box-shadow: 0 6px 24px rgba(179, 255, 179, 0.2);
            transform: translateY(-2px);
        }

        .chart-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-card-subtitle {
            font-size: 12px;
            color: #6B7280;
            margin: 5px 0 0 0;
        }

        .chart-month-badge {
            background: linear-gradient(135deg, var(--soft-green) 0%, var(--light-green) 100%);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .chart-status-badge {
            background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .chart-badge-text {
            font-size: 12px;
            color: #0F172A;
            font-weight: 600;
        }

        .chart-stats-row {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 15px;
        }

        .chart-stat-pill {
            background: rgba(179, 255, 179, 0.2);
            padding: 8px 14px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chart-stat-pill.secondary {
            background: rgba(179, 217, 255, 0.2);
        }

        .chart-stat-value {
            font-size: 13px;
            font-weight: 700;
            color: #15803d;
        }

        .chart-stat-pill.secondary .chart-stat-value {
            color: #0c4a6e;
        }

        .chart-stat-label {
            font-size: 11px;
            color: #6B7280;
        }
    </style>

    <!-- Charts Section - 2 Compact Charts Side by Side -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px;">
        <!-- Project Completions Chart - Compact -->
        <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(179, 255, 179, 0.08) 100%); border-radius: 12px; border: 1px solid rgba(179, 255, 179, 0.3); box-shadow: 0 2px 8px rgba(179, 255, 179, 0.1); padding: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="font-size: 12px; font-weight: 700; color: #0F172A; margin: 0; text-transform: uppercase;">📊 Completions</h3>
                <span style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--light-green) 100%); padding: 4px 10px; border-radius: 12px; font-size: 10px; color: #0F172A; font-weight: 600;">{{ now()->format('M Y') }}</span>
            </div>
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <div style="background: rgba(179, 255, 179, 0.2); padding: 4px 10px; border-radius: 12px; display: flex; align-items: center; gap: 4px;">
                    <span style="font-size: 11px; font-weight: 700; color: #15803d;" id="totalCompletedBadge">0</span>
                    <span style="font-size: 9px; color: #6B7280;">Done</span>
                </div>
                <div style="background: rgba(179, 217, 255, 0.2); padding: 4px 10px; border-radius: 12px; display: flex; align-items: center; gap: 4px;">
                    <span style="font-size: 11px; font-weight: 700; color: #0c4a6e;" id="avgPerDayBadge">0</span>
                    <span style="font-size: 9px; color: #6B7280;">Avg/Day</span>
                </div>
            </div>
            <div style="position: relative; height: 120px;">
                <canvas id="dailyProjectsChart"></canvas>
            </div>
        </div>

        <!-- Project Status Distribution Chart - Compact -->
        <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 179, 217, 0.08) 100%); border-radius: 12px; border: 1px solid rgba(255, 179, 217, 0.3); box-shadow: 0 2px 8px rgba(255, 179, 217, 0.1); padding: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="font-size: 12px; font-weight: 700; color: #0F172A; margin: 0; text-transform: uppercase;">📈 Status</h3>
                <span style="background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%); padding: 4px 10px; border-radius: 12px; font-size: 10px; color: #0F172A; font-weight: 600;">{{ $totalProjects }} Total</span>
            </div>
            <div style="display: flex; justify-content: center; align-items: center; height: 140px;">
                <canvas id="projectStatusChart" style="max-width: 140px; max-height: 140px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Widget Container -->
    <div id="dashboard-widgets" style="display: flex; flex-direction: column; gap: 32px;">

    <!-- Widget: Quick Actions -->
    <div class="dashboard-widget" data-widget-id="actions" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 255, 217, 0.1) 100%); border-radius: 20px; border: 1px solid rgba(179, 217, 255, 0.3); box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15); padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 8px 20px 8px; border-bottom: 2px solid rgba(179, 217, 255, 0.3); margin-bottom: 20px;">
            <h2 style="font-size: 18px; color: #0F172A; font-weight: 700; margin: 0; cursor: move; flex: 1; display: flex; align-items: center; gap: 10px;">
                <span style="background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); padding: 8px; border-radius: 10px; font-size: 16px;">⚡</span>
                Quick Actions
            </h2>
        </div>
        <div class="widget-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <!-- Create New Project -->
            <div style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.15) 0%, rgba(179, 255, 217, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-blue); box-shadow: 0 2px 8px rgba(179, 217, 255, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📁 Create New Project</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Start a new project and assign team members to begin tracking work.</p>
                <a href="/admin/projects/create" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%); color: #0c4a6e; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 217, 255, 0.5);">Create Project</a>
            </div>

            <!-- Create New User -->
            <div style="background: linear-gradient(135deg, rgba(179, 255, 179, 0.15) 0%, rgba(179, 255, 217, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-green); box-shadow: 0 2px 8px rgba(179, 255, 179, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">👥 Create New User</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Add a new team member to the system and manage their access and permissions.</p>
                <a href="/admin/users/create" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); color: #15803d; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 255, 179, 0.5);">Create User</a>
            </div>

            <!-- Manage Users -->
            <div style="background: linear-gradient(135deg, rgba(217, 179, 255, 0.15) 0%, rgba(230, 217, 255, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-purple); box-shadow: 0 2px 8px rgba(217, 179, 255, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">👤 Manage Users</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">View, edit, and manage all users in your organization with full control.</p>
                <a href="/admin/users" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); color: #6b21a8; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(217, 179, 255, 0.5);">Manage Users</a>
            </div>

            <!-- Review Time Entries -->
            <div style="background: linear-gradient(135deg, rgba(255, 217, 179, 0.15) 0%, rgba(255, 204, 179, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-orange); box-shadow: 0 2px 8px rgba(255, 217, 179, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">⏱️ Review Time Entries</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Review and approve pending time entries submitted by team members.</p>
                <a href="/admin/time-entries" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); color: #92400e; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 217, 179, 0.5);">Review Entries</a>
            </div>

            <!-- View Reports -->
            <div style="background: linear-gradient(135deg, rgba(255, 179, 217, 0.15) 0%, rgba(248, 187, 208, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-pink); box-shadow: 0 2px 8px rgba(255, 179, 217, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📊 View Reports</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Generate and view comprehensive reports on time tracking and productivity.</p>
                <a href="/admin/reports" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%); color: #9d174d; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 179, 217, 0.5);">View Reports</a>
            </div>

            <!-- View All Projects -->
            <div style="background: linear-gradient(135deg, rgba(230, 217, 255, 0.15) 0%, rgba(217, 179, 255, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-lavender); box-shadow: 0 2px 8px rgba(230, 217, 255, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📋 View All Projects</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Browse all projects, manage their status, progress, and assigned team members.</p>
                <a href="/admin/projects" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-lavender) 0%, var(--soft-purple) 100%); color: #6b21a8; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(230, 217, 255, 0.5);">View Projects</a>
            </div>

            <!-- View Analytics -->
            <div style="background: linear-gradient(135deg, rgba(255, 179, 217, 0.12) 0%, rgba(255, 204, 179, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-pink); box-shadow: 0 2px 8px rgba(255, 179, 217, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📈 View Analytics</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Access detailed analytics and insights about team productivity and time tracking trends.</p>
                <a href="/admin/analytics" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-pink) 0%, var(--soft-peach) 100%); color: #9d174d; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 179, 217, 0.5);">View Analytics</a>
            </div>

            <!-- Manage Team Members -->
            <div style="background: linear-gradient(135deg, rgba(179, 255, 217, 0.15) 0%, rgba(179, 255, 179, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-mint); box-shadow: 0 2px 8px rgba(179, 255, 217, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">👥 Manage Team Members</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Organize and manage team member assignments, roles, and project participation.</p>
                <a href="/admin/team-members" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-mint) 0%, var(--soft-green) 100%); color: #0d9488; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 255, 217, 0.5);">Manage Team</a>
            </div>

            <!-- View Notifications -->
            <div style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.12) 0%, rgba(230, 217, 255, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-blue); box-shadow: 0 2px 8px rgba(179, 217, 255, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">🔔 View Notifications</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Check all system notifications, alerts, and important updates for your organization.</p>
                <a href="/admin/notifications" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); color: #4338ca; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 217, 255, 0.5);">View Notifications</a>
            </div>

            <!-- Project Templates -->
            <div style="background: linear-gradient(135deg, rgba(255, 204, 179, 0.15) 0%, rgba(255, 217, 179, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-peach); box-shadow: 0 2px 8px rgba(255, 204, 179, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📦 Project Templates</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Create and manage project templates to streamline project creation and setup.</p>
                <a href="/admin/projects/templates" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-peach) 0%, var(--soft-orange) 100%); color: #c2410c; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 204, 179, 0.5);">Manage Templates</a>
            </div>

            <!-- Build Custom Report -->
            <div style="background: linear-gradient(135deg, rgba(217, 179, 255, 0.12) 0%, rgba(255, 179, 217, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-purple); box-shadow: 0 2px 8px rgba(217, 179, 255, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">🛠️ Build Custom Report</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Create custom reports with selected metrics, filters, and grouping options for detailed analysis.</p>
                <a href="/admin/reports/builder" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-pink) 100%); color: #7c3aed; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(217, 179, 255, 0.5);">Build Report</a>
            </div>

            <!-- My Reports -->
            <div style="background: linear-gradient(135deg, rgba(179, 255, 217, 0.12) 0%, rgba(179, 217, 255, 0.1) 100%); border-radius: 16px; border: 1px solid var(--soft-mint); box-shadow: 0 2px 8px rgba(179, 255, 217, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📋 My Reports</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">View, manage, and export all your saved custom reports with export options.</p>
                <a href="/admin/reports/list" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-mint) 0%, var(--soft-blue) 100%); color: #0891b2; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 255, 217, 0.5);">My Reports</a>
            </div>

            <!-- System Settings -->
            <div style="background: linear-gradient(135deg, rgba(200, 230, 201, 0.15) 0%, rgba(179, 217, 255, 0.1) 100%); border-radius: 16px; border: 1px solid var(--light-green); box-shadow: 0 2px 8px rgba(200, 230, 201, 0.15); padding: 20px; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">⚙️ System Settings</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Configure system settings, preferences, and organizational policies.</p>
                <a href="/admin/settings" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--light-green) 0%, var(--soft-mint) 100%); color: #166534; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(200, 230, 201, 0.5);">Settings</a>
            </div>
        </div>

        <!-- Financial Section Divider -->
        <div style="margin-top: 24px; padding-top: 24px; border-top: 2px dashed rgba(179, 255, 179, 0.4);">
            <h3 style="font-size: 16px; color: #0F172A; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                <span style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); padding: 8px; border-radius: 10px; font-size: 14px;">💰</span>
                Financial Management
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                
                <!-- Financial Dashboard -->
                <div style="background: linear-gradient(135deg, rgba(179, 255, 179, 0.18) 0%, rgba(179, 255, 217, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-green); box-shadow: 0 2px 8px rgba(179, 255, 179, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">💰 Financial Dashboard</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">View complete financial overview with charts, summaries, and key metrics.</p>
                    <a href="/admin/financial" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); color: #15803d; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 255, 179, 0.5);">View Dashboard</a>
                </div>

                <!-- Income Management -->
                <div style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.18) 0%, rgba(179, 255, 217, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-blue); box-shadow: 0 2px 8px rgba(179, 217, 255, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">💵 Income Management</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Track and manage all income sources, monthly budgets, and actual earnings.</p>
                    <a href="/admin/financial/income" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%); color: #0c4a6e; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 217, 255, 0.5);">Manage Income</a>
                </div>

                <!-- Expense Management -->
                <div style="background: linear-gradient(135deg, rgba(255, 179, 217, 0.18) 0%, rgba(255, 204, 179, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-pink); box-shadow: 0 2px 8px rgba(255, 179, 217, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">💸 Expense Management</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Record and categorize expenses, set budgets, and track spending patterns.</p>
                    <a href="/admin/financial/expense" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%); color: #9d174d; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 179, 217, 0.5);">Manage Expenses</a>
                </div>

                <!-- Savings Goals -->
                <div style="background: linear-gradient(135deg, rgba(179, 255, 217, 0.18) 0%, rgba(179, 255, 179, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-mint); box-shadow: 0 2px 8px rgba(179, 255, 217, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">🎯 Savings Goals</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Set and track savings goals, monitor progress, and achieve financial targets.</p>
                    <a href="/admin/financial/savings" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-mint) 0%, var(--soft-green) 100%); color: #0d9488; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 255, 217, 0.5);">Manage Savings</a>
                </div>

                <!-- Donations -->
                <div style="background: linear-gradient(135deg, rgba(217, 179, 255, 0.18) 0%, rgba(230, 217, 255, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-purple); box-shadow: 0 2px 8px rgba(217, 179, 255, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">❤️ Donations</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Track charitable contributions, manage donation records, and view giving history.</p>
                    <a href="/admin/financial/donation" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); color: #6b21a8; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(217, 179, 255, 0.5);">Manage Donations</a>
                </div>

                <!-- Transactions -->
                <div style="background: linear-gradient(135deg, rgba(255, 217, 179, 0.18) 0%, rgba(255, 204, 179, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-orange); box-shadow: 0 2px 8px rgba(255, 217, 179, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">🔄 Transactions</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">View all financial transactions, approve pending ones, and manage records.</p>
                    <a href="/admin/financial/transactions" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); color: #92400e; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 217, 179, 0.5);">View Transactions</a>
                </div>

                <!-- Budgets -->
                <div style="background: linear-gradient(135deg, rgba(230, 217, 255, 0.18) 0%, rgba(179, 217, 255, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-lavender); box-shadow: 0 2px 8px rgba(230, 217, 255, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📊 Budgets</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Create and manage budgets, set spending limits, and track budget performance.</p>
                    <a href="/admin/financial/budgets" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-lavender) 0%, var(--soft-purple) 100%); color: #6b21a8; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(230, 217, 255, 0.5);">Manage Budgets</a>
                </div>

                <!-- Invoices -->
                <div style="background: linear-gradient(135deg, rgba(255, 204, 179, 0.18) 0%, rgba(255, 217, 179, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-peach); box-shadow: 0 2px 8px rgba(255, 204, 179, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📄 Invoices</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Create, send, and manage invoices. Track payment status and outstanding amounts.</p>
                    <a href="/admin/financial/invoices" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-peach) 0%, var(--soft-orange) 100%); color: #c2410c; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(255, 204, 179, 0.5);">Manage Invoices</a>
                </div>

                <!-- Create Income -->
                <div style="background: linear-gradient(135deg, rgba(200, 230, 201, 0.18) 0%, rgba(179, 255, 179, 0.12) 100%); border-radius: 16px; border: 1px solid var(--light-green); box-shadow: 0 2px 8px rgba(200, 230, 201, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">➕ Add Income</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Quickly add a new income entry with source, amount, and date details.</p>
                    <a href="/admin/financial/income/create" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--light-green) 0%, var(--soft-mint) 100%); color: #166534; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(200, 230, 201, 0.5);">Add Income</a>
                </div>

                <!-- Create Expense -->
                <div style="background: linear-gradient(135deg, rgba(248, 187, 208, 0.18) 0%, rgba(255, 179, 217, 0.12) 100%); border-radius: 16px; border: 1px solid var(--light-pink); box-shadow: 0 2px 8px rgba(248, 187, 208, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">➖ Add Expense</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Record a new expense with category, amount, and payment details.</p>
                    <a href="/admin/financial/expense/create" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--light-pink) 0%, var(--soft-pink) 100%); color: #9d174d; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(248, 187, 208, 0.5);">Add Expense</a>
                </div>

                <!-- Create Transaction -->
                <div style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.15) 0%, rgba(230, 217, 255, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-blue); box-shadow: 0 2px 8px rgba(179, 217, 255, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📝 New Transaction</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Create a new financial transaction record for tracking and approval.</p>
                    <a href="/admin/financial/transactions/create" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); color: #0c4a6e; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 217, 255, 0.5);">New Transaction</a>
                </div>

                <!-- Create Invoice -->
                <div style="background: linear-gradient(135deg, rgba(217, 179, 255, 0.15) 0%, rgba(255, 179, 217, 0.12) 100%); border-radius: 16px; border: 1px solid var(--soft-purple); box-shadow: 0 2px 8px rgba(217, 179, 255, 0.15); padding: 20px; transition: all 0.3s;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">🧾 Create Invoice</h3>
                    <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Generate a new invoice for clients with itemized billing and payment terms.</p>
                    <a href="/admin/financial/invoices/create" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-pink) 100%); color: #7c3aed; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(217, 179, 255, 0.5);">Create Invoice</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Widget: Real-Time Projects Summary -->
    <div class="dashboard-widget" data-widget-id="projects" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 217, 255, 0.1) 100%); border-radius: 20px; border: 1px solid rgba(179, 217, 255, 0.3); box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15); padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 8px 20px 8px; border-bottom: 2px solid rgba(217, 179, 255, 0.3); margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <h2 style="font-size: 18px; color: #0F172A; font-weight: 700; margin: 0; cursor: move; flex: 1; display: flex; align-items: center; gap: 10px;">
                <span style="background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); padding: 8px; border-radius: 10px; font-size: 16px;">📁</span>
                Projects Overview
            </h2>
            <a href="/admin/projects" style="display: inline-block; padding: 10px 18px; background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); color: #0c4a6e; text-decoration: none; border-radius: 10px; font-size: 12px; font-weight: 600; transition: all 0.3s; border: 1px solid rgba(179, 217, 255, 0.5);">View All Projects</a>
        </div>
        <div class="widget-content" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
            @forelse($allProjects->take(6) as $project)
                @php
                    $statusColors = [
                        'planning' => '#F59E0B',
                        'in-progress' => '#06B6D4',
                        'completed' => '#16A34A',
                        'on-hold' => '#6B7280'
                    ];
                    $statusIcons = [
                        'planning' => '📋',
                        'in-progress' => '⚙️',
                        'completed' => '✅',
                        'on-hold' => '⏸️'
                    ];
                    $color = $statusColors[$project->status] ?? '#2563EB';
                    $icon = $statusIcons[$project->status] ?? '📁';
                    $progressWidth = $project->progress;
                    $lightColor = $color . '20';
                    $gradientColor1 = $color;
                    $gradientColor2 = $color . 'cc';
                @endphp
                <div class="project-card" data-project-id="{{ $project->id }}" data-status="{{ $project->status }}" style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); transition: all 0.3s;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <h3 style="font-size: 14px; color: #0F172A; font-weight: 600; margin: 0; flex: 1;">{{ $icon }} {{ $project->name }}</h3>
                        <button onclick="openProgressEditor({{ $project->id }}, {{ $project->progress }})" style="background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); border: none; padding: 4px 8px; border-radius: 6px; cursor: pointer; font-size: 10px; font-weight: 600; color: #0c4a6e; transition: all 0.2s;" title="Quick Edit">✏️</button>
                    </div>
                    
                    <!-- Dates Section -->
                    <div style="display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap;">
                        @if($project->start_date)
                        <div style="display: flex; align-items: center; gap: 4px; background: rgba(179, 255, 179, 0.2); padding: 4px 8px; border-radius: 6px;">
                            <span style="font-size: 10px;">🚀</span>
                            <span style="font-size: 10px; color: #15803d; font-weight: 500;">{{ \Carbon\Carbon::parse($project->start_date)->format('M d') }}</span>
                        </div>
                        @endif
                        @if($project->due_date)
                        <div style="display: flex; align-items: center; gap: 4px; background: rgba(255, 179, 217, 0.2); padding: 4px 8px; border-radius: 6px;">
                            <span style="font-size: 10px;">📅</span>
                            <span style="font-size: 10px; color: #9d174d; font-weight: 500;">{{ \Carbon\Carbon::parse($project->due_date)->format('M d') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Progress Section with Quick Edit -->
                    <div style="margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <span style="font-size: 11px; color: #6B7280; font-weight: 500;">Progress</span>
                            <span class="progress-value" data-project="{{ $project->id }}" style="font-size: 12px; color: #0F172A; font-weight: 700; background: linear-gradient(135deg, {{ $lightColor }} 0%, {{ $color }}15 100%); padding: 2px 8px; border-radius: 8px;">{{ $project->progress }}%</span>
                        </div>
                        <!-- Clickable Progress Bar -->
                        <div class="progress-bar-interactive" data-project-id="{{ $project->id }}" style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; cursor: pointer; position: relative;" onclick="handleProgressBarClick(event, {{ $project->id }})" title="Click to set progress">
                            <div class="progress-fill-animated" data-project="{{ $project->id }}" style="width: {{ $progressWidth }}%; height: 100%; background: linear-gradient(90deg, {{ $gradientColor1 }}, {{ $gradientColor2 }}); transition: width 0.3s ease; border-radius: 4px;"></div>
                        </div>
                        <!-- Quick Progress Buttons -->
                        <div style="display: flex; gap: 4px; margin-top: 6px; flex-wrap: wrap;">
                            <button onclick="setQuickProgress({{ $project->id }}, 0)" style="padding: 2px 6px; font-size: 9px; border: 1px solid #e5e7eb; background: white; border-radius: 4px; cursor: pointer; color: #6B7280;">0%</button>
                            <button onclick="setQuickProgress({{ $project->id }}, 25)" style="padding: 2px 6px; font-size: 9px; border: 1px solid var(--soft-orange); background: rgba(255, 217, 179, 0.2); border-radius: 4px; cursor: pointer; color: #92400e;">25%</button>
                            <button onclick="setQuickProgress({{ $project->id }}, 50)" style="padding: 2px 6px; font-size: 9px; border: 1px solid var(--soft-blue); background: rgba(179, 217, 255, 0.2); border-radius: 4px; cursor: pointer; color: #0c4a6e;">50%</button>
                            <button onclick="setQuickProgress({{ $project->id }}, 75)" style="padding: 2px 6px; font-size: 9px; border: 1px solid var(--soft-purple); background: rgba(217, 179, 255, 0.2); border-radius: 4px; cursor: pointer; color: #6b21a8;">75%</button>
                            <button onclick="setQuickProgress({{ $project->id }}, 100)" style="padding: 2px 6px; font-size: 9px; border: 1px solid var(--soft-green); background: rgba(179, 255, 179, 0.2); border-radius: 4px; cursor: pointer; color: #15803d;">100%</button>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 1px solid #f3f4f6;">
                        <span class="status-badge" data-project="{{ $project->id }}" style="display: inline-block; padding: 4px 10px; background: {{ $lightColor }}; color: {{ $color }}; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: capitalize;">{{ str_replace('-', ' ', $project->status) }}</span>
                        <span style="font-size: 10px; color: #9ca3af;">{{ $project->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 48px 20px; background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%); border-radius: 12px; border: 1px dashed #cbd5e1;">
                    <div style="font-size: 48px; margin-bottom: 16px;">📁</div>
                    <h3 style="font-size: 18px; color: #0F172A; font-weight: 600; margin-bottom: 8px;">No Projects Yet</h3>
                    <p style="color: #6B7280; font-size: 14px; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">Create your first project to get started with project management and team collaboration.</p>
                    <a href="/admin/projects/create" style="display: inline-block; padding: 12px 24px; background-color: #2563EB; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s;">Create Project</a>
                </div>
            @endforelse
        </div>
    </div>

    </div>
@endsection

@section('scripts')
<script>
    // Widget customization state
    const WIDGET_STORAGE_KEY = 'admin_dashboard_widgets';

    function updateActivityFeed() {
        fetch('/api/admin/recent-activities', {
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
                <div style="display: flex; gap: 12px; padding: 12px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #2563EB; transition: all 0.3s;">
                    <div style="font-size: 20px; flex-shrink: 0; padding-top: 2px;">
                        ${activity.icon}
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="font-weight: 600; color: #0F172A; font-size: 14px;">${activity.user_name}</span>
                            <span style="color: #9ca3af; font-size: 12px;">${activity.time_ago}</span>
                        </div>
                        <p style="color: #6B7280; font-size: 13px; margin: 0;">${activity.description}</p>
                    </div>
                </div>
            `).join('');

            container.style.opacity = '0.7';
            container.style.transition = 'opacity 0.3s ease';

            setTimeout(() => {
                container.innerHTML = activitiesHtml;
                container.style.opacity = '1';
            }, 150);
        })
        .catch(error => console.error('Error fetching activities:', error));
    }
    
    // Widget Customization Functions
    function initializeWidgetCustomization() {
        loadWidgetPreferences();
        setupSortable();
        loadHiddenWidgets();
    }

    function setupSortable() {
        const container = document.getElementById('dashboard-widgets');
        if (!container) return;

        new Sortable(container, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.dashboard-widget',
            onEnd: function() {
                saveWidgetOrder();
            }
        });

        // Add CSS for drag feedback
        const style = document.createElement('style');
        style.textContent = `
            .sortable-ghost {
                opacity: 0.5;
                background: #f0f0f0;
            }
            .dashboard-widget {
                cursor: move;
            }
        `;
        document.head.appendChild(style);
    }

    function toggleWidget(widgetId) {
        const widget = document.querySelector(`[data-widget-id="${widgetId}"]`);
        if (!widget) return;

        const isHidden = widget.style.display === 'none';
        widget.style.display = isHidden ? 'block' : 'none';

        // Update button icon
        const toggleBtn = widget.querySelector('.widget-toggle');
        if (toggleBtn) {
            toggleBtn.textContent = isHidden ? '👁️' : '👁️‍🗨️';
        }

        saveWidgetPreferences();
    }

    function saveWidgetOrder() {
        const container = document.getElementById('dashboard-widgets');
        if (!container) return;

        const order = Array.from(container.querySelectorAll('.dashboard-widget')).map(w => w.getAttribute('data-widget-id'));
        const prefs = getWidgetPreferences();
        prefs.order = order;
        
        localStorage.setItem(WIDGET_STORAGE_KEY, JSON.stringify(prefs));
    }

    function saveWidgetPreferences() {
        const prefs = getWidgetPreferences();
        const hiddenWidgets = [];

        document.querySelectorAll('.dashboard-widget').forEach(widget => {
            const widgetId = widget.getAttribute('data-widget-id');
            if (widget.style.display === 'none') {
                hiddenWidgets.push(widgetId);
            }
        });

        prefs.hidden = hiddenWidgets;
        localStorage.setItem(WIDGET_STORAGE_KEY, JSON.stringify(prefs));
    }

    function loadWidgetPreferences() {
        const prefs = getWidgetPreferences();

        // Restore order
        if (prefs.order && prefs.order.length > 0) {
            const container = document.getElementById('dashboard-widgets');
            if (!container) return;

            const widgets = {};
            container.querySelectorAll('.dashboard-widget').forEach(w => {
                widgets[w.getAttribute('data-widget-id')] = w;
            });

            prefs.order.forEach(widgetId => {
                if (widgets[widgetId]) {
                    container.appendChild(widgets[widgetId]);
                }
            });
        }
    }

    function loadHiddenWidgets() {
        const prefs = getWidgetPreferences();
        prefs.hidden.forEach(widgetId => {
            const widget = document.querySelector(`[data-widget-id="${widgetId}"]`);
            if (widget) {
                widget.style.display = 'none';
                const toggleBtn = widget.querySelector('.widget-toggle');
                if (toggleBtn) {
                    toggleBtn.textContent = '👁️‍🗨️';
                }
            }
        });
    }

    function getWidgetPreferences() {
        const stored = localStorage.getItem(WIDGET_STORAGE_KEY);
        if (!stored) {
            return { order: [], hidden: [] };
        }
        return JSON.parse(stored);
    }

    function resetWidgetPreferences() {
        localStorage.removeItem(WIDGET_STORAGE_KEY);
        location.reload();
    }
    
    // Initialize the dashboard completion chart
    function initializeDashboardChart() {
        const ctx = document.getElementById('dashboardCompletionChart');
        if (!ctx) {
            console.log('Dashboard completion chart canvas not found, skipping initialization');
            return;
        }

        const monthlyData = @json($monthlyData);
        const chartLabels = @json($chartLabels);
        const completions = monthlyData;

        // Create gradient colors for the line
        const canvasCtx = ctx.getContext('2d');
        const gradient = canvasCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.4)');
        gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.2)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

        // Create colorful gradient for the line itself
        const lineGradient = canvasCtx.createLinearGradient(0, 0, canvasCtx.canvas.width, 0);
        lineGradient.addColorStop(0, '#2563EB');
        lineGradient.addColorStop(0.25, '#06B6D4');
        lineGradient.addColorStop(0.5, '#16A34A');
        lineGradient.addColorStop(0.75, '#F59E0B');
        lineGradient.addColorStop(1, '#DC2626');

        const chart = new Chart(canvasCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Projects Completed',
                    data: completions,
                    borderColor: lineGradient,
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 6,
                    pointBackgroundColor: function(context) {
                        const value = context.raw;
                        if (value === 0) return '#e5e7eb';
                        if (value === 1) return '#06B6D4';
                        if (value === 2) return '#2563EB';
                        if (value === 3) return '#16A34A';
                        if (value === 4) return '#F59E0B';
                        return '#DC2626';
                    },
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: function(context) {
                        const value = context.raw;
                        if (value === 0) return '#d1d5db';
                        if (value === 1) return '#0891b2';
                        if (value === 2) return '#1d4ed8';
                        if (value === 3) return '#15803d';
                        if (value === 4) return '#d97706';
                        return '#b91c1c';
                    },
                    pointHoverBorderWidth: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#6B7280',
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        padding: 14,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2563EB',
                        borderWidth: 2,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                // The label already contains the formatted date
                                return context[0].label;
                            },
                            label: function(context) {
                                const value = context.parsed.y;
                                return 'Projects completed: ' + value;
                            },
                            afterLabel: function(context) {
                                const value = context.parsed.y;
                                if (value === 1) {
                                    return '1 project was completed on this date';
                                } else if (value > 1) {
                                    return value + ' projects were completed on this date';
                                } else {
                                    return 'No projects completed on this date';
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...completions) + 1,
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 12
                            },
                            stepSize: 1
                        },
                        grid: {
                            color: '#e5e7eb',
                            drawBorder: false,
                            lineWidth: 1
                        },
                        title: {
                            display: true,
                            text: 'Number of Projects Completed',
                            color: '#0F172A',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Date of Completion',
                            color: '#0F172A',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        return chart;
    }
    
    // Initialize the yearly completion chart
    function initializeYearlyChart() {
        const ctx = document.getElementById('yearlyCompletionChart');
        if (!ctx) {
            console.log('Yearly completion chart canvas not found, skipping initialization');
            return;
        }

        const yearlyData = @json($yearlyData);
        const monthLabels = @json($monthLabels);
        const completions = yearlyData;

        const canvasCtx = ctx.getContext('2d');
        
        // Create gradient for bars
        const gradient = canvasCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.8)');
        gradient.addColorStop(0.5, 'rgba(6, 182, 212, 0.6)');
        gradient.addColorStop(1, 'rgba(22, 163, 74, 0.8)');

        const chart = new Chart(canvasCtx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Projects Completed',
                    data: completions,
                    backgroundColor: gradient,
                    borderColor: '#2563EB',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(37, 99, 235, 1)',
                    hoverBorderColor: '#1d4ed8',
                    hoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#6B7280',
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rect'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        padding: 14,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#2563EB',
                        borderWidth: 2,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                return 'Projects completed: ' + value;
                            },
                            afterLabel: function(context) {
                                const value = context.parsed.y;
                                if (value === 1) {
                                    return '1 project was completed this month';
                                } else if (value > 1) {
                                    return value + ' projects were completed this month';
                                } else {
                                    return 'No projects completed this month';
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...completions) + 1,
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 12
                            },
                            stepSize: 1
                        },
                        grid: {
                            color: '#e5e7eb',
                            drawBorder: false,
                            lineWidth: 1
                        },
                        title: {
                            display: true,
                            text: 'Number of Projects Completed',
                            color: '#0F172A',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Month',
                            color: '#0F172A',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        return chart;
    }
    
    // Initialize Daily Projects Completions Chart - Enhanced
    function initializeDailyProjectsChart() {
        const dailyData = @json($dailyData);
        const dailyLabels = @json($dailyLabels);
        
        console.log('Initializing Daily Projects Chart');
        console.log('Daily Data:', dailyData);
        console.log('Daily Labels:', dailyLabels);
        
        const ctx = document.getElementById('dailyProjectsChart');
        if (!ctx) {
            console.error('Canvas element #dailyProjectsChart not found');
            return;
        }
        
        console.log('Canvas found:', ctx);
        console.log('Canvas parent height:', ctx.parentElement.style.height);
        
        // Calculate statistics with proper error handling
        const maxValue = dailyData.length > 0 ? Math.max(...dailyData) : 0;
        const totalValue = dailyData.length > 0 ? dailyData.reduce((a, b) => a + b, 0) : 0;
        const activeDays = dailyData.filter(v => v > 0).length;
        const avgValue = totalValue > 0 && activeDays > 0 ? (totalValue / activeDays).toFixed(1) : 0;
        
        console.log('Statistics - Max:', maxValue, 'Total:', totalValue, 'Active Days:', activeDays, 'Avg:', avgValue);
        
        // Update badge values
        document.getElementById('totalCompletedBadge').textContent = totalValue;
        document.getElementById('avgPerDayBadge').textContent = avgValue;

        try {
            const canvasContext = ctx.getContext('2d');
            if (!canvasContext) {
                console.error('Could not get 2d context from canvas');
                return;
            }
            
            // Create premium gradient for the area fill using soft theme colors
            const gradient = canvasContext.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(179, 255, 179, 0.3)');
            gradient.addColorStop(0.5, 'rgba(179, 255, 179, 0.1)');
            gradient.addColorStop(1, 'rgba(179, 255, 179, 0.01)');

            // Create line gradient using soft theme colors
            const lineGradient = canvasContext.createLinearGradient(0, 0, canvasContext.canvas.width, 0);
            lineGradient.addColorStop(0, '#B3FFB3');
            lineGradient.addColorStop(0.5, '#B3D9FF');
            lineGradient.addColorStop(1, '#B3FFB3');

            // Set a reasonable max value for the y-axis
            const yAxisMax = maxValue > 0 ? maxValue + 2 : 10;

            console.log('Creating chart with yAxisMax:', yAxisMax);

            const chart = new Chart(canvasContext, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Projects Completed',
                        data: dailyData,
                        borderColor: lineGradient,
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.5,
                        pointRadius: 5,
                        pointBackgroundColor: function(context) {
                            const value = context.raw;
                            if (value === 0) return '#E5E7EB';
                            if (value <= 2) return '#B3FFB3';
                            if (value <= 4) return '#B3D9FF';
                            return '#FFD9B3';
                        },
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#B3FFB3',
                        pointHoverBorderWidth: 4,
                        segment: {
                            borderColor: ctx => {
                                return ctx.p0DataIndex % 2 === 0 ? 'rgba(16, 185, 129, 0.7)' : 'rgba(6, 182, 212, 0.7)';
                            }
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#0F172A',
                                font: { size: 11, weight: 'bold' },
                                padding: 12,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 6,
                                callback: function(label) {
                                    return '  ' + label;
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#B3FFB3',
                            borderWidth: 2,
                            padding: 12,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            displayColors: true,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return ' Projects Completed: ' + value;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: yAxisMax,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.03)',
                                drawBorder: false,
                                drawTicks: false,
                                lineWidth: 1
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: { size: 10, weight: '500' },
                                stepSize: 1,
                                padding: 8,
                                callback: function(value) {
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: { size: 9, weight: '500' },
                                maxRotation: 45,
                                minRotation: 0,
                                padding: 6
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'lineChartPlugin',
                    afterDatasetsDraw(chart) {
                        const ctx = chart.ctx;
                        chart.data.datasets.forEach((dataset, i) => {
                            // Add a subtle glow effect
                            ctx.strokeStyle = 'rgba(16, 185, 129, 0.1)';
                            ctx.lineWidth = 8;
                            ctx.stroke();
                        });
                    }
                }]
            });
            
            console.log('Chart created successfully:', chart);
            return chart;
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    }
    
    // Initialize Project Status Chart
    function initializeProjectStatusChart() {
        const ctx = document.getElementById('projectStatusChart');
        if (!ctx) {
            console.log('Project Status chart canvas not found, skipping initialization');
            return;
        }

        const statusData = @json($projectStatusData);
        const statusLabels = @json($projectStatusLabels);
        
        // Soft theme colors matching the CSS variables
        const softColors = [
            '#FFD9B3', // soft-orange for planning
            '#B3D9FF', // soft-blue for in-progress
            '#B3FFB3', // soft-green for completed
            '#D9B3FF'  // soft-purple for on-hold
        ];

        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: softColors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverBorderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#0F172A',
                            font: {
                                size: 11,
                                weight: '600'
                            },
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#FFB3D9',
                        borderWidth: 2,
                        padding: 12,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        displayColors: true,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1000
                }
            }
        });

        return chart;
    }
    
    // Progress Editor Modal
    function openProgressEditor(projectId, currentProgress) {
        const newProgress = prompt(`Set progress for project (0-100):`, currentProgress);
        if (newProgress !== null) {
            const progress = Math.min(100, Math.max(0, parseInt(newProgress) || 0));
            updateProjectProgress(projectId, progress);
        }
    }
    
    // Handle clicking on progress bar
    function handleProgressBarClick(event, projectId) {
        const bar = event.currentTarget;
        const rect = bar.getBoundingClientRect();
        const clickX = event.clientX - rect.left;
        const percentage = Math.round((clickX / rect.width) * 100);
        const progress = Math.min(100, Math.max(0, percentage));
        updateProjectProgress(projectId, progress);
    }
    
    // Quick progress buttons
    function setQuickProgress(projectId, progress) {
        updateProjectProgress(projectId, progress);
    }
    
    // Update project progress with auto-status
    function updateProjectProgress(projectId, progress) {
        // Determine new status based on progress
        let newStatus = 'planning';
        let statusColor = '#F59E0B';
        let statusIcon = '📋';
        let statusText = 'Planning';
        
        if (progress === 100) {
            newStatus = 'completed';
            statusColor = '#16A34A';
            statusIcon = '✅';
            statusText = 'Completed';
        } else if (progress >= 75) {
            newStatus = 'in-progress';
            statusColor = '#06B6D4';
            statusIcon = '⚙️';
            statusText = 'Almost Done';
        } else if (progress >= 50) {
            newStatus = 'in-progress';
            statusColor = '#06B6D4';
            statusIcon = '⚙️';
            statusText = 'In Progress';
        } else if (progress >= 25) {
            newStatus = 'in-progress';
            statusColor = '#F59E0B';
            statusIcon = '🔨';
            statusText = 'Getting Started';
        } else if (progress > 0) {
            newStatus = 'planning';
            statusColor = '#F59E0B';
            statusIcon = '📋';
            statusText = 'Just Started';
        }
        
        // Update UI immediately for responsive feel
        const progressValue = document.querySelector(`.progress-value[data-project="${projectId}"]`);
        const progressFill = document.querySelector(`.progress-fill-animated[data-project="${projectId}"]`);
        const statusBadge = document.querySelector(`.status-badge[data-project="${projectId}"]`);
        const projectCard = document.querySelector(`.project-card[data-project-id="${projectId}"]`);
        
        if (progressValue) {
            progressValue.textContent = progress + '%';
            progressValue.style.background = `linear-gradient(135deg, ${statusColor}20 0%, ${statusColor}15 100%)`;
        }
        
        if (progressFill) {
            progressFill.style.width = progress + '%';
            progressFill.style.background = `linear-gradient(90deg, ${statusColor}, ${statusColor}cc)`;
        }
        
        if (statusBadge) {
            statusBadge.textContent = statusText;
            statusBadge.style.background = statusColor + '20';
            statusBadge.style.color = statusColor;
        }
        
        if (projectCard) {
            projectCard.setAttribute('data-status', newStatus);
            // Update the title icon
            const titleElement = projectCard.querySelector('h3');
            if (titleElement) {
                const projectName = titleElement.textContent.replace(/^[^\s]+\s/, '');
                titleElement.innerHTML = statusIcon + ' ' + projectName;
            }
        }
        
        // Send update to server
        fetch(`/admin/api/projects/${projectId}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                progress: progress,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show subtle success indicator
                if (projectCard) {
                    projectCard.style.boxShadow = `0 0 0 2px ${statusColor}40`;
                    setTimeout(() => {
                        projectCard.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.05)';
                    }, 1000);
                }
            }
        })
        .catch(error => {
            console.error('Error updating progress:', error);
        });
    }
    
    // Initial setup
    window.addEventListener('DOMContentLoaded', function() {
        initializeWidgetCustomization();
        updateActivityFeed();
        initializeDashboardChart();
        initializeYearlyChart();
        initializeDailyProjectsChart();
        initializeProjectStatusChart();
    });
</script>
@endsection
