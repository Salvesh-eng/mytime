<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mytime - Time Managment system')</title>
    <link rel="icon" type="image/png" href="/pictures/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #0F172A 0%, #1e293b 100%);
            color: #E6EEF8;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(37, 99, 235, 0.1);
            border: 2px solid #06B6D4;
        }

        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 20px;
            color: #06B6D4;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav ul li {
            margin-bottom: 10px;
        }

        .sidebar nav ul li a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 6px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar nav ul li a:hover {
            background-color: rgba(37, 99, 235, 0.2);
            border-left-color: #06B6D4;
            color: #06B6D4;
        }

        .sidebar nav ul li a.active {
            background: rgba(37, 99, 235, 0.3);
            border-left-color: #06B6D4;
            color: #06B6D4;
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
            padding-bottom: 80px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }

        .header {
            background-color: white;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #2563EB;
        }

        .header h1 {
            font-size: 18px;
            color: #0F172A;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-menu a {
            color: #0F172A;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            background-color: #f0f4f8;
            transition: background-color 0.3s;
        }

        .user-menu a:hover {
            background-color: #e0e7f1;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 22px;
            color: #0F172A;
            transition: transform 0.2s;
            padding: 6px;
            border-radius: 6px;
        }

        .notification-bell:hover {
            transform: scale(1.1);
            background-color: #f0f9ff;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #DC2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
            animation: badgePulse 2s infinite;
        }

        @keyframes badgePulse {
            0%, 100% {
                box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 2px 12px rgba(220, 38, 38, 0.5);
                transform: scale(1.05);
            }
        }

        .notification-dropdown {
            position: absolute;
            top: 60px;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            width: 380px;
            max-height: 450px;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
        }

        .notification-dropdown.active {
            display: block;
            animation: dropdownSlide 0.2s ease-out;
        }

        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            padding: 16px;
            border-bottom: 2px solid #f3f4f6;
            font-weight: 700;
            color: #0F172A;
            background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .notification-item {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            gap: 12px;
            transition: background-color 0.2s;
            cursor: pointer;
            align-items: flex-start;
        }

        .notification-item:hover {
            background-color: #f9fafb;
        }

        .notification-item:hover .notification-actions {
            opacity: 1;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .notification-action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.2s;
            color: #6B7280;
            font-weight: 600;
        }

        .notification-action-btn:hover {
            background-color: #e5e7eb;
            color: #0F172A;
        }

        .notification-action-btn.view {
            color: #2563EB;
        }

        .notification-action-btn.view:hover {
            background-color: #dbeafe;
        }

        .notification-action-btn.mark-read {
            color: #06B6D4;
        }

        .notification-action-btn.mark-read:hover {
            background-color: #cffafe;
        }

        .notification-action-btn.delete {
            color: #DC2626;
        }

        .notification-action-btn.delete:hover {
            background-color: #fee2e2;
        }

        .notification-content h4 {
            font-size: 13px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 4px;
        }

        .notification-content p {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 11px;
            color: #9CA3AF;
        }

        .notification-footer {
            padding: 12px 16px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
            background-color: #f9fafb;
            border-radius: 0 0 12px 12px;
        }

        .notification-footer a {
            color: #2563EB;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .notification-footer a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #9CA3AF;
            font-size: 14px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #16A34A;
            color: #15803d;
        }

        .alert-error {
            background-color: #fef2f2;
            border-color: #DC2626;
            color: #991b1b;
        }

        .alert-info {
            background-color: #f0f9ff;
            border-color: #06B6D4;
            color: #164e63;
        }

        .card {
            background-color: white;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: #2563EB;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        table thead {
            background-color: #2563EB;
            color: white;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        table tbody tr:hover {
            background-color: #f0f9ff;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #2563EB;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-success {
            background-color: #16A34A;
            color: white;
        }

        .btn-success:hover {
            background-color: #15803d;
        }

        .btn-danger {
            background-color: #DC2626;
            color: white;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }

        .btn-secondary {
            background-color: #6B7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #0F172A;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-errors {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            color: #991b1b;
        }

        .form-errors ul {
            list-style: none;
            margin: 0;
        }

        .form-errors li {
            margin-bottom: 5px;
        }

        .pagination {
            display: flex;
            gap: 5px;
            margin-top: 20px;
            justify-content: center;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            text-decoration: none;
            color: #0F172A;
        }

        .pagination a:hover {
            background-color: #f0f9ff;
        }

        .pagination .active {
            background-color: #2563EB;
            color: white;
            border-color: #2563EB;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-active {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-inactive {
            background-color: #e2e8f0;
            color: #475569;
        }

        .filter-form {
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filter-form .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-form .form-group {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .container {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }
        }

        .dashboard-content {
            flex: 1;
            width: 100%;
        }

        .footer {
            background-color: #0F172A;
            color: #E6EEF8;
            padding: 10px 20px;
            text-align: center;
            border-top: 3px solid #2563EB;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 100;
        }

        .footer p {
            margin: 0;
            font-size: 11px;
        }

        .footer-brand {
            color: #06B6D4;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .footer-link {
            color: #06B6D4;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .footer-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        .action-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #2563EB;
            transition: all 0.3s;
        }

        .action-card:hover {
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);
            transform: translateY(-2px);
        }

        .action-card h3 {
            color: #0F172A;
            margin-bottom: 12px;
            font-size: 16px;
        }

        .action-card p {
            color: #6B7280;
            font-size: 13px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .action-card-link {
            display: inline-block;
            padding: 8px 16px;
            background-color: #2563EB;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .action-card-link:hover {
            background-color: #1d4ed8;
            transform: translateX(2px);
        }

        .action-card.success {
            border-left-color: #16A34A;
        }

        .action-card.success .action-card-link {
            background-color: #16A34A;
        }

        .action-card.success .action-card-link:hover {
            background-color: #15803d;
        }

        .action-card.warning {
            border-left-color: #F59E0B;
        }

        .action-card.warning .action-card-link {
            background-color: #F59E0B;
        }

        .action-card.warning .action-card-link:hover {
            background-color: #d97706;
        }

        .action-card.danger {
            border-left-color: #DC2626;
        }

        .action-card.danger .action-card-link {
            background-color: #DC2626;
        }

        .action-card.danger .action-card-link:hover {
            background-color: #b91c1c;
        }

        .real-time-clock {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 6px 12px;
            background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
            border-radius: 6px;
            color: white;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
            min-width: 120px;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed h2 {
            display: none;
        }

        .sidebar.collapsed nav ul li a {
            justify-content: center;
            font-size: 20px;
        }

        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar nav ul li a span:first-child {
            font-size: 18px;
            min-width: 24px;
            text-align: center;
        }

        .clock-time {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-family: 'Courier New', monospace;
            line-height: 1;
        }

        .clock-period {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            opacity: 0.9;
        }

        .clock-date {
            font-size: 8px;
            opacity: 0.85;
            margin-top: 2px;
            text-align: center;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            animation: slideIn 0.3s ease-out, slideOut 0.3s ease-out 4.7s forwards;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 400px;
        }

        .toast-success {
            background-color: #16A34A;
            color: white;
            border-left: 4px solid #15803d;
        }

        .toast-error {
            background-color: #DC2626;
            color: white;
            border-left: 4px solid #b91c1c;
        }

        .toast-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            padding: 0;
            margin-left: 8px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="/pictures/logo.png" alt="Mytime Logo">
            </div>
            <h2>Mytime</h2>
            <nav>
                <ul>
                    @if(auth()->user()->isAdmin())
                        <li><a href="/admin/dashboard" class="@if(request()->is('admin/dashboard')) active @endif"><span>📊</span> <span>Dashboard</span></a></li>
                        <li><a href="/admin/projects" class="@if(request()->is('admin/projects')) active @endif"><span>📁</span> <span>Projects</span></a></li>
                        <li><a href="/admin/projects/create" class="@if(request()->is('admin/projects/create')) active @endif"><span>✨</span> <span>New Project</span></a></li>
                        <li><a href="/admin/analytics" class="@if(request()->is('admin/analytics')) active @endif"><span>📈</span> <span>Analytics</span></a></li>
                        <li><a href="/admin/notifications" class="@if(request()->is('admin/notifications')) active @endif"><span>🔔</span> <span>Notifications</span></a></li>
                        <li><a href="/admin/team-members" class="@if(request()->is('admin/team-members*')) active @endif"><span>👥</span> <span>Team</span></a></li>
                        <li><a href="/admin/users" class="@if(request()->is('admin/users*')) active @endif"><span>👤</span> <span>Users</span></a></li>
                        <li><a href="/admin/time-entries" class="@if(request()->is('admin/time-entries*')) active @endif"><span>⏱️</span> <span>Time Logs</span></a></li>
                        <li><a href="/admin/reports" class="@if(request()->is('admin/reports*')) active @endif"><span>📄</span> <span>Reports</span></a></li>
                    @else
                        <li><a href="/dashboard" class="@if(request()->is('dashboard')) active @endif"><span>📊</span> <span>Dashboard</span></a></li>
                        <li><a href="/add-time" class="@if(request()->is('add-time')) active @endif"><span>⏱️</span> <span>Start Time</span></a></li>
                        <li><a href="/my-logs" class="@if(request()->is('my-logs*')) active @endif"><span>📋</span> <span>My Logs</span></a></li>
                        <li><a href="/profile" class="@if(request()->is('profile*')) active @endif"><span>⚙️</span> <span>Profile</span></a></li>
                    @endif
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <!-- Hamburger Menu Toggle -->
            <div style="position: fixed; top: 20px; left: 20px; z-index: 1000; display: none;" id="hamburger-menu-btn">
                <button onclick="toggleSidebar()" style="background: #0F172A; color: white; border: none; padding: 10px 15px; border-radius: 6px; font-size: 20px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">☰</button>
            </div>

            <!-- Breadcrumb Navigation -->
            <div id="breadcrumb-container" style="padding: 16px 0; display: none; border-bottom: 1px solid #e5e7eb; margin-bottom: 16px;">
                <nav style="font-size: 13px;" id="breadcrumb">
                    <a href="#" style="color: #2563EB; text-decoration: none; margin-right: 8px;">📍 Home</a>
                </nav>
            </div>

            <div class="header">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="user-menu" style="display: flex; align-items: center; gap: 20px;">
                    <!-- Global Search Bar -->
                    <div style="position: relative; flex: 1; max-width: 300px;">
                        <input type="text" id="global-search" placeholder="🔍 Search (Ctrl+K)..." style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: white;">
                        <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 6px 6px; max-height: 300px; overflow-y: auto; display: none; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>
                    </div>

                    <!-- Active Timer Indicator -->
                    <div id="timer-indicator" style="display: none; padding: 8px 14px; background: linear-gradient(135deg, #DC2626 0%, #991b1b 100%); color: white; border-radius: 6px; font-size: 13px; font-weight: 600; animation: pulse 2s infinite;">
                        🔴 <span id="timer-elapsed">00:00:00</span>
                    </div>

                    <div class="real-time-clock">
                        <div class="clock-time" id="clock-time">--:--:--</div>
                        <div class="clock-period" id="clock-period">AM</div>
                        <div class="clock-date" id="clock-date">Loading...</div>
                    </div>
                    <div style="position: relative;">
                        <div class="notification-bell" onclick="toggleNotificationPanel()" id="notification-bell">
                            🔔
                            <span class="notification-badge" id="notification-count" style="display: none;">0</span>
                        </div>
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="notification-header">Notifications</div>
                            <div id="notification-list"></div>
                            <div class="notification-footer">
                                <a href="/admin/notifications">View All →</a>
                            </div>
                        </div>
                    </div>
                    <!-- User Profile Dropdown -->
                    <div style="position: relative;">
                        <button id="user-menu-btn" onclick="toggleUserMenu()" style="background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            👤 {{ auth()->user()->name }}
                            <span style="font-size: 10px;">▼</span>
                        </button>
                        <div id="user-menu-dropdown" style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-width: 200px; z-index: 100; margin-top: 8px; display: none;">
                            <a href="/profile" style="display: block; padding: 12px 16px; color: #0F172A; text-decoration: none; border-bottom: 1px solid #f3f4f6; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor=''">
                                ⚙️ Profile Settings
                            </a>
                            <a href="#" onclick="showHelp(); return false;" style="display: block; padding: 12px 16px; color: #0F172A; text-decoration: none; border-bottom: 1px solid #f3f4f6; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor=''">
                                ❓ Help & Shortcuts
                            </a>
                            <form method="POST" action="/logout" style="display: block;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 12px 16px; background: none; border: none; color: #DC2626; cursor: pointer; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#fee2e2'" onmouseout="this.style.backgroundColor=''">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="form-errors">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="toast-notification toast-success" id="toast-notification">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                    <button class="toast-close" onclick="document.getElementById('toast-notification').remove()">×</button>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-notification toast-error" id="toast-notification">
                    <span>✕</span>
                    <span>{{ session('error') }}</span>
                    <button class="toast-close" onclick="document.getElementById('toast-notification').remove()">×</button>
                </div>
            @endif

            <div class="dashboard-content">
                @yield('content')
            </div>

            <div class="footer">
                <p style="margin-top: 0; border-top: none; padding-top: 0;">
                    All rights reserved © 2025 by <span style="color: #06B6D4; font-weight: 600;">Ek Qodor kei Coding</span>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key.toLowerCase()) {
                    case 'k':
                        e.preventDefault();
                        document.getElementById('global-search').focus();
                        break;
                    case 'n':
                        e.preventDefault();
                        const createLink = document.querySelector('a[href*="/create"]');
                        if (createLink) {
                            window.location.href = createLink.href;
                        }
                        break;
                    case 't':
                        e.preventDefault();
                        window.location.href = '/add-time';
                        break;
                    case '?':
                        e.preventDefault();
                        showHelp();
                        break;
                }
            }
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            
            if (isCollapsed) {
                mainContent.style.marginLeft = '70px';
                sidebar.style.width = '70px';
            } else {
                mainContent.style.marginLeft = '250px';
                sidebar.style.width = '250px';
            }
        }

        // Load Sidebar State
        window.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                sidebar.classList.add('collapsed');
                sidebar.style.width = '70px';
                mainContent.style.marginLeft = '70px';
            }
            
            // Show hamburger on mobile
            if (window.innerWidth < 768) {
                document.getElementById('hamburger-menu-btn').style.display = 'block';
            }
        });

        // Global Search
        document.getElementById('global-search').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            const resultsDiv = document.getElementById('search-results');
            
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }
            
            fetch(`/api/search?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(data => {
                    let html = '<div style="padding: 12px;">';
                    
                    if (data.projects?.length) {
                        html += '<div style="padding: 8px; border-bottom: 1px solid #f3f4f6; font-weight: 600; color: #6B7280; font-size: 12px;">PROJECTS</div>';
                        data.projects.forEach(p => {
                            html += `<a href="/admin/projects/${p.id}/edit" style="display: block; padding: 10px 8px; color: #0F172A; text-decoration: none; border-radius: 4px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='';">📁 ${p.name}</a>`;
                        });
                    }
                    
                    if (data.users?.length) {
                        html += '<div style="padding: 8px; border-top: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; font-weight: 600; color: #6B7280; font-size: 12px; margin-top: 8px;">USERS</div>';
                        data.users.forEach(u => {
                            html += `<a href="/admin/users/${u.id}/edit" style="display: block; padding: 10px 8px; color: #0F172A; text-decoration: none; border-radius: 4px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='';">👤 ${u.name}</a>`;
                        });
                    }
                    
                    if (!data.projects?.length && !data.users?.length) {
                        html += '<div style="padding: 12px; color: #9ca3af; text-align: center;">No results found</div>';
                    }
                    
                    html += '</div>';
                    resultsDiv.innerHTML = html;
                    resultsDiv.style.display = 'block';
                })
                .catch(e => console.error('Search error:', e));
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#global-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').style.display = 'none';
            }
            if (!e.target.closest('#user-menu-btn') && !e.target.closest('#user-menu-dropdown')) {
                document.getElementById('user-menu-dropdown').style.display = 'none';
            }
        });

        // Toggle User Menu
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-menu-dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Show Help
        function showHelp() {
            const help = `
KEYBOARD SHORTCUTS:
━━━━━━━━━━━━━━━━━━━━
Ctrl+K or Cmd+K: Open Search
Ctrl+N or Cmd+N: New Project
Ctrl+T or Cmd+T: Start Timer
Ctrl+? or Cmd+?: Show this help
            `;
            alert(help);
        }

        // Timer Indicator - Check every 1 second if timer is running
        setInterval(function() {
            const timerDiv = document.getElementById('timer-indicator');
            if (localStorage.getItem('timer-running') === 'true') {
                timerDiv.style.display = 'inline-block';
                const elapsed = parseInt(localStorage.getItem('timer-elapsed') || '0');
                const hours = Math.floor(elapsed / 3600);
                const minutes = Math.floor((elapsed % 3600) / 60);
                const seconds = elapsed % 60;
                document.getElementById('timer-elapsed').textContent = 
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
            } else {
                timerDiv.style.display = 'none';
            }
        }, 1000);

        // Real-time Clock Widget
        function updateClock() {
            const now = new Date();
            
            // Format time HH:MM:SS
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            
            // Determine AM/PM
            const period = now.getHours() >= 12 ? 'PM' : 'AM';
            
            // Format date
            const options = { weekday: 'short', month: 'short', day: 'numeric' };
            const dateString = now.toLocaleDateString('en-US', options);
            
            // Update DOM
            document.getElementById('clock-time').textContent = timeString;
            document.getElementById('clock-period').textContent = period;
            document.getElementById('clock-date').textContent = dateString;
        }

        // Update clock immediately on load
        updateClock();
        
        // Update clock every second
        setInterval(updateClock, 1000);

        function toggleNotificationPanel() {
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
            if (dropdown.classList.contains('active')) {
                loadNotifications();
            }
        }

        function loadNotifications() {
            const list = document.getElementById('notification-list');
            list.innerHTML = '<div style="text-align: center; padding: 20px; color: #6B7280;">Loading...</div>';

            fetch('/api/notifications', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach((notif) => {
                        const icon = notif.type === 'upcoming' ? '📅' : (notif.type === 'due' ? '⏰' : '📝');
                        html += `
                            <div class="notification-item" id="notif-${notif.id}">
                                <div class="notification-icon">${icon}</div>
                                <div class="notification-content">
                                    <h4>${notif.title}</h4>
                                    <p>${notif.message}</p>
                                    <div class="notification-time">${notif.time}</div>
                                </div>
                                <div class="notification-actions">
                                    <button class="notification-action-btn view" onclick="viewProject(${notif.project_id})" title="View">👁️</button>
                                    <button class="notification-action-btn mark-read" onclick="markAsRead(${notif.id})" title="Mark as Read">✓</button>
                                    <button class="notification-action-btn delete" onclick="deleteNotification(${notif.id})" title="Delete">🗑️</button>
                                </div>
                            </div>
                        `;
                    });
                    list.innerHTML = html;
                    updateNotificationCount(data.notifications.length);
                } else {
                    list.innerHTML = '<div class="notification-empty">No notifications</div>';
                    updateNotificationCount(0);
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                list.innerHTML = '<div class="notification-empty">Failed to load notifications</div>';
            });
        }

        function viewProject(projectId) {
            window.location.href = `/admin/projects/${projectId}/edit`;
        }

        function markAsRead(notifId) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(`/admin/notifications/${notifId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const element = document.getElementById(`notif-${notifId}`);
                    if (element) {
                        element.style.opacity = '0.6';
                        element.style.backgroundColor = '#f3f4f6';
                        element.style.transition = 'all 0.3s ease';
                    }
                    // Reload notifications after a short delay
                    setTimeout(() => loadNotifications(), 500);
                } else {
                    alert('Failed to mark notification as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error marking notification as read');
            });
        }

        function deleteNotification(notifId) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(`/admin/notifications/${notifId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const element = document.getElementById(`notif-${notifId}`);
                    if (element) {
                        element.style.animation = 'slideOut 0.3s ease-out forwards';
                        setTimeout(() => element.remove(), 300);
                    }
                    // Reload notifications after a short delay
                    setTimeout(() => loadNotifications(), 500);
                } else {
                    alert('Failed to delete notification');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting notification');
            });
        }

        function updateNotificationCount(count) {
            const badge = document.getElementById('notification-count');
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            const bell = document.getElementById('notification-bell');
            const dropdown = document.getElementById('notification-dropdown');
            if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Load notifications on page load
        window.addEventListener('load', function() {
            loadNotifications();
        });
    </script>

    @yield('scripts')
</body>
</html>
