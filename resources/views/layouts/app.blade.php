<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            width: 280px;
            background: linear-gradient(180deg, #FFB3D9 0%, #B3D9FF 50%, #B3FFB3 100%);
            color: #ca1111ff;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow: hidden;
            box-shadow: 4px 0 15px rgba(255, 179, 217, 0.3);
            display: flex;
            flex-direction: column;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
        }

        .sidebar:hover {
            width: 280px;
            overflow-y: auto;
            background: linear-gradient(180deg, #FFB3D9 0%, #B3D9FF 50%, #B3FFB3 100%);
        }

        .sidebar.collapsed {
            width: 70px;s
            overflow: hidden;
        }

        .sidebar.collapsed:hover {
            width: 70px;
        }

        .sidebar.collapsed h2 {
            display: none;
        }

        .sidebar.collapsed .nav-label {
            display: none;
        }

        .sidebar-toggle-btn {
            position: absolute;
            right: 8px;
            top: 20px;
            background: rgba(20, 101, 128, 0.1);
            border: 1px solid #ADD8E6;
            color: #ADD8E6;
            padding: 8px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            z-index: 1001;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            min-height: 36px;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(173, 216, 230, 0.2);
            box-shadow: 0 0 8px rgba(173, 216, 230, 0.3);
        }

        .sidebar-logo {
            width: 120px;
            height: 120px;
            margin: 20px auto;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            border: 3px solid #ADD8E6;
            box-shadow: 0 4px 12px rgba(173, 216, 230, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo {
            width: 50px;
            height: 50px;
            margin: 12px auto;
            border-width: 2px;
        }

        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar h2 {
            margin: 0 20px 10px 20px;
            padding: 10px 0;
            font-size: 24px;
            color: #2b2a2aff;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: center;
            border-bottom: 2px solid rgba(51, 51, 51, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar nav::-webkit-scrollbar-track {
            background: rgba(255, 215, 0, 0.05);
        }

        .sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.3);
            border-radius: 3px;
        }

        .sidebar nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 215, 0, 0.5);
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0 10px;
        }

        .sidebar nav ul li {
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .sidebar nav ul li a {
            color: #f8fafc;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
            font-weight: 500;
            font-size: 14px;
            position: relative;
        }

        .sidebar nav ul li a:hover {
            background-color: rgba(173, 216, 230, 0.25);
            border-left-color: #ffffff;
            color: #ffffff;
            transform: translateX(4px);
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .sidebar nav ul li a.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.15) 100%);
            border-left-color: #ffffff;
            color: #ffffff;
            font-weight: 700;
            box-shadow: inset 0 0 15px rgba(255, 255, 255, 0.25);
            position: relative;
        }

        .sidebar nav ul li a.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #ADD8E6;
            border-radius: 2px;
        }

        .sidebar nav ul li a span:first-child {
            font-size: 20px;
            min-width: 24px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar nav ul li a .nav-label {
            flex: 1;
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed nav ul li a {
            justify-content: center;
            padding: 14px 12px;
        }

        .sidebar.collapsed nav ul li a span:first-child {
            margin: 0;
        }

        /* Tooltip for collapsed state */
        .sidebar.collapsed nav ul li a::before {
            content: attr(data-label);
            position: absolute;
            left: 70px;
            background: rgba(173, 216, 230, 0.9);
            color: #0F172A;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 1002;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar.collapsed nav ul li a:hover::before {
            opacity: 1;
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 20px;
            padding-bottom: 80px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.sidebar-collapsed {
            margin-left: 70px;
        }

        .header {
            background: linear-gradient(135deg, #FFB3D9 0%, #B3D9FF 50%, #B3FFB3 100%);
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #FFB3D9;
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
            left: 50%;
            transform: translateX(-50%);
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 99999;
            animation: slideInTop 0.3s ease-out, slideOutTop 0.3s ease-out 4.7s forwards;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 400px;
            pointer-events: auto;
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

        @keyframes slideInTop {
            from {
                transform: translateX(-50%) translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOutTop {
            from {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
            to {
                transform: translateX(-50%) translateY(-100px);
                opacity: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        <div class="sidebar" id="sidebar">
            <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" title="Collapse/Expand">
                <span id="toggle-icon">◀</span>
            </button>
            <div class="sidebar-logo">
                <img src="/pictures/logo.png" alt="Mytime Logo">
            </div>
            <h2>Mytime</h2>
            <nav>
                <ul>
                    @if(auth()->user()->isAdmin())
                        <li><a href="/admin/dashboard" class="@if(request()->is('admin/dashboard')) active @endif" data-label="Dashboard"><span>📊</span> <span class="nav-label">Dashboard</span></a></li>
                        <li><a href="/admin/projects" class="@if(request()->is('admin/projects')) active @endif" data-label="Projects"><span>📁</span> <span class="nav-label">Projects</span></a></li>
                        <li><a href="/admin/projects/create" class="@if(request()->is('admin/projects/create')) active @endif" data-label="New Project"><span>✨</span> <span class="nav-label">New Project</span></a></li>
                        <li><a href="/admin/financial" class="@if(request()->is('admin/financial*')) active @endif" data-label="Financial"><span>💰</span> <span class="nav-label">Financial</span></a></li>
                        <li><a href="/admin/analytics" class="@if(request()->is('admin/analytics')) active @endif" data-label="Analytics"><span>📈</span> <span class="nav-label">Analytics</span></a></li>
                        <li><a href="/admin/team-members" class="@if(request()->is('admin/team-members*')) active @endif" data-label="Team"><span>👥</span> <span class="nav-label">Team</span></a></li>
                        <li><a href="/admin/users" class="@if(request()->is('admin/users*')) active @endif" data-label="Users"><span>👤</span> <span class="nav-label">Users</span></a></li>
                        <li><a href="/admin/time-entries" class="@if(request()->is('admin/time-entries*')) active @endif" data-label="Time Logs"><span>⏱️</span> <span class="nav-label">Time Logs</span></a></li>
                        <li><a href="/admin/reports" class="@if(request()->is('admin/reports*')) active @endif" data-label="Reports"><span>📊</span> <span class="nav-label">Reports</span></a></li>
                        <li><a href="/admin/reports/builder" class="@if(request()->is('admin/reports/builder')) active @endif" data-label="Build Report"><span>🛠️</span> <span class="nav-label">Build Report</span></a></li>
                        <li><a href="/admin/reports/list" class="@if(request()->is('admin/reports/list')) active @endif" data-label="My Reports"><span>📋</span> <span class="nav-label">My Reports</span></a></li>
                        <li><a href="/motivation" class="@if(request()->is('motivation*')) active @endif" data-label="Motivation"><span>✨</span> <span class="nav-label">Motivation</span></a></li>
                        <li><a href="/profile" class="@if(request()->is('profile*')) active @endif" data-label="Profile"><span>⚙️</span> <span class="nav-label">Profile</span></a></li>
                    @else
                        <li><a href="/dashboard" class="@if(request()->is('dashboard')) active @endif" data-label="Dashboard"><span>📊</span> <span class="nav-label">Dashboard</span></a></li>
                        <li><a href="/add-time" class="@if(request()->is('add-time')) active @endif" data-label="Start Time"><span>⏱️</span> <span class="nav-label">Start Time</span></a></li>
                        <li><a href="/my-logs" class="@if(request()->is('my-logs*')) active @endif" data-label="My Logs"><span>📋</span> <span class="nav-label">My Logs</span></a></li>
                        <li><a href="/personal-finance" class="@if(request()->is('personal-finance*')) active @endif" data-label="Finance"><span>💰</span> <span class="nav-label">Finance</span></a></li>
                        <li><a href="/reports" class="@if(request()->is('reports*')) active @endif" data-label="Reports"><span>📊</span> <span class="nav-label">Reports</span></a></li>
                        <li><a href="/motivation" class="@if(request()->is('motivation*')) active @endif" data-label="Motivation"><span>✨</span> <span class="nav-label">Motivation</span></a></li>
                        <li><a href="/profile" class="@if(request()->is('profile*')) active @endif" data-label="Profile"><span>⚙️</span> <span class="nav-label">Profile</span></a></li>
                    @endif
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <!-- Hamburger Menu Toggle -->
            <div style="position: fixed; top: 20px; left: 20px; z-index: 1000; display: none;" id="hamburger-menu-btn">
                <button onclick="toggleSidebarCollapse()" style="background: #0F172A; color: white; border: none; padding: 10px 15px; border-radius: 6px; font-size: 20px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">☰</button>
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
                    <!-- News Panel -->
                    <div style="position: relative;">
                        <div class="notification-bell" onclick="toggleNewsPanel()" id="news-bell" style="cursor: pointer;">
                            📰
                        </div>
                        <div class="notification-dropdown" id="news-dropdown" style="width: 380px; max-height: 500px;">
                            <div class="notification-header">Latest News & Updates</div>
                            <div id="news-content" style="padding: 16px;">
                                <div style="text-align: center; color: #6B7280; font-size: 14px; padding: 20px;">Loading news...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Weather Dropdown -->
                    <div style="position: relative;">
                        <div class="notification-bell" onclick="toggleWeatherPanel()" id="weather-bell" style="cursor: pointer;">
                            🌤️
                        </div>
                        <div class="notification-dropdown" id="weather-dropdown" style="width: 320px;">
                            <div class="notification-header">Weather Information</div>
                            <div id="weather-content" style="padding: 20px; text-align: center; color: #6B7280;">
                                <div style="font-size: 14px; margin-bottom: 10px;">Loading weather data...</div>
                            </div>
                        </div>
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
                <div class="toast-notification toast-success" id="toast-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                    <button class="toast-close" onclick="document.getElementById('toast-success').remove()">×</button>
                </div>
            @endif

            <div class="dashboard-content">
                @yield('content')
            </div>

            <div class="footer">
                <p style="margin-top: 0; border-top: none; padding-top: 0;">
                    All rights reserved © 2025 by <span style="color: #06B6D4; font-weight: 600;">Ek Coder Ki Coding</span>
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
        function toggleSidebarCollapse() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleIcon = document.getElementById('toggle-icon');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
            
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            
            // Update toggle icon
            toggleIcon.textContent = isCollapsed ? '▶' : '◀';
        }

        // Load Sidebar State
        window.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                const toggleIcon = document.getElementById('toggle-icon');
                
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
                toggleIcon.textContent = '▶';
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

        function toggleWeatherPanel() {
            const dropdown = document.getElementById('weather-dropdown');
            dropdown.classList.toggle('active');
            if (dropdown.classList.contains('active')) {
                loadWeatherData();
            }
        }

        function toggleNewsPanel() {
            const dropdown = document.getElementById('news-dropdown');
            dropdown.classList.toggle('active');
            if (dropdown.classList.contains('active')) {
                loadNewsData();
            }
        }

        // Auto-refresh news every hour (3600000 milliseconds)
        let newsRefreshInterval;
        
        function startNewsAutoRefresh() {
            // Initial load
            if (document.getElementById('news-dropdown').classList.contains('active')) {
                loadNewsData();
            }
            
            // Set interval to refresh every hour
            if (newsRefreshInterval) {
                clearInterval(newsRefreshInterval);
            }
            
            newsRefreshInterval = setInterval(() => {
                console.log('Auto-refreshing news from Fiji Village...');
                if (document.getElementById('news-dropdown').classList.contains('active')) {
                    loadNewsData();
                } else {
                    // Silently refresh in background
                    refreshNewsInBackground();
                }
            }, 3600000); // 1 hour = 3600000 ms
        }

        function refreshNewsInBackground() {
            fetch('https://api.rss2json.com/v1/api.json?rss_url=' + encodeURIComponent('https://www.fijivillage.com/feed/'))
                .then(response => response.json())
                .then(data => {
                    if (data.items && data.items.length > 0) {
                        // Store in sessionStorage for quick access
                        sessionStorage.setItem('fijiNewsCache', JSON.stringify(data.items));
                        console.log('News cache updated at ' + new Date().toLocaleTimeString());
                    }
                })
                .catch(error => console.error('Background news refresh error:', error));
        }

        function loadNewsData() {
            const content = document.getElementById('news-content');
            content.innerHTML = '<div style="text-align: center; color: #6B7280; font-size: 14px; padding: 20px;">🔄 Loading news from Fiji Village...</div>';

            // Check if we have cached news
            const cachedNews = sessionStorage.getItem('fijiNewsCache');
            const lastUpdate = localStorage.getItem('fijiNewsLastUpdate');
            const now = new Date().getTime();
            
            // If cache exists and is less than 1 hour old, use it
            if (cachedNews && lastUpdate && (now - parseInt(lastUpdate)) < 3600000) {
                try {
                    const articles = JSON.parse(cachedNews);
                    displayFijiNews(articles);
                    showLastUpdateTime();
                    return;
                } catch (e) {
                    console.error('Cache parse error:', e);
                }
            }

            // Fetch from Fiji Village RSS or news endpoint
            fetchFijiVillageNews();
        }

        function showLastUpdateTime() {
            const lastUpdate = localStorage.getItem('fijiNewsLastUpdate');
            if (lastUpdate) {
                const updateTime = new Date(parseInt(lastUpdate));
                const timeAgo = getTimeAgo(updateTime);
                const updateElement = document.querySelector('[data-update-time]');
                if (updateElement) {
                    updateElement.textContent = `Last updated: ${timeAgo}`;
                }
            }
        }

        function fetchFijiVillageNews() {
            // Try multiple sources for Fiji Village news
            const rssUrl = 'https://www.fijivillage.com/feed/';
            
            // Using a CORS proxy to fetch the RSS feed
            fetch(`https://api.rss2json.com/v1/api.json?rss_url=${encodeURIComponent(rssUrl)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.items && data.items.length > 0) {
                        // Cache the news and update timestamp
                        sessionStorage.setItem('fijiNewsCache', JSON.stringify(data.items));
                        localStorage.setItem('fijiNewsLastUpdate', new Date().getTime().toString());
                        console.log('News updated from Fiji Village at ' + new Date().toLocaleTimeString());
                        displayFijiNews(data.items);
                        showLastUpdateTime();
                    } else {
                        loadFijiSampleNews();
                    }
                })
                .catch(error => {
                    console.error('Error fetching Fiji Village news:', error);
                    loadFijiSampleNews();
                });
        }

        function displayFijiNews(articles) {
            const lastUpdate = localStorage.getItem('fijiNewsLastUpdate');
            const updateTime = lastUpdate ? getTimeAgo(new Date(parseInt(lastUpdate))) : 'just now';
            
            let html = `
                <div style="padding: 12px 16px; background: linear-gradient(135deg, #06B6D4 0%, #0891b2 100%); color: white; margin: -16px -16px 12px -16px; border-radius: 12px 12px 0 0; font-size: 12px; font-weight: 600;">
                    🏝️ Latest News from Fiji Village
                    <div style="font-size: 10px; font-weight: 400; margin-top: 4px; opacity: 0.9;" data-update-time>🔄 Last updated: ${updateTime}</div>
                </div>
            `;

            articles.slice(0, 5).forEach((article, index) => {
                const pubDate = new Date(article.pubDate);
                const timeAgo = getTimeAgo(pubDate);
                const title = article.title || 'Untitled';
                const description = article.description || article.content || 'Read more for details';
                const link = article.link || 'https://www.fijivillage.com';
                
                // Clean HTML tags from description
                const cleanDescription = description
                    .replace(/<[^>]*>/g, '')
                    .substring(0, 100) + '...';

                html += `
                    <div style="padding: 12px 0; border-bottom: 1px solid #f3f4f6; ${index === 4 ? 'border-bottom: none;' : ''}">
                        <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                            <span style="font-size: 11px; font-weight: 600; color: #06B6D4; background: #cffafe; padding: 2px 8px; border-radius: 12px;">🏝️ Fiji</span>
                            <span style="font-size: 11px; color: #9CA3AF;">${timeAgo}</span>
                        </div>
                        <h4 style="margin: 0 0 4px 0; font-size: 13px; color: #0F172A; font-weight: 600; line-height: 1.3;">
                            ${title}
                        </h4>
                        <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; line-height: 1.3;">
                            ${cleanDescription}
                        </p>
                        <a href="${link}" target="_blank" style="font-size: 11px; color: #06B6D4; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                            Read More →
                        </a>
                    </div>
                `;
            });

            html += `
                <div style="padding: 12px; text-align: center; border-top: 1px solid #f3f4f6; background: #f9fafb; border-radius: 0 0 12px 12px; font-size: 11px; color: #9CA3AF; margin-top: 12px;">
                    <a href="https://www.fijivillage.com/news/" target="_blank" style="color: #06B6D4; text-decoration: none;">📰 Fiji Village News</a> • Auto-refreshes hourly
                </div>
            `;

            document.getElementById('news-content').innerHTML = html;
        }

        function loadFijiSampleNews() {
            const fijiNews = [
                {
                    title: '🏝️ Fiji Tourism Recovery Exceeds Expectations',
                    description: 'Tourism sector experiences record visitor numbers this month, boosting local economy and employment...',
                    source: 'Fiji Village',
                    timeAgo: '2 hours ago',
                    url: 'https://www.fijivillage.com'
                },
                {
                    title: '🌊 New Marine Conservation Initiative Launched',
                    description: 'Government announces ambitious plan to protect coral reefs and marine ecosystems across the islands...',
                    source: 'Fiji Village',
                    timeAgo: '4 hours ago',
                    url: 'https://www.fijivillage.com'
                },
                {
                    title: '💼 Fiji Business Forum Attracts International Investors',
                    description: 'Major business summit brings opportunities for local enterprises to expand into regional markets...',
                    source: 'Fiji Village',
                    timeAgo: '6 hours ago',
                    url: 'https://www.fijivillage.com'
                },
                {
                    title: '🎓 Educational Scholarships Announced for 2025',
                    description: 'Government and private sector announce new scholarship programs for underprivileged students...',
                    source: 'Fiji Village',
                    timeAgo: '8 hours ago',
                    url: 'https://www.fijivillage.com'
                },
                {
                    title: '🏗️ Major Infrastructure Development Project Begins',
                    description: 'New roads and utilities project to connect remote communities to major towns and services...',
                    source: 'Fiji Village',
                    timeAgo: '10 hours ago',
                    url: 'https://www.fijivillage.com'
                }
            ];

            let html = `
                <div style="padding: 12px 16px; background: linear-gradient(135deg, #06B6D4 0%, #0891b2 100%); color: white; margin: -16px -16px 12px -16px; border-radius: 12px 12px 0 0; font-size: 12px; font-weight: 600;">
                    🏝️ Latest News from Fiji Village
                </div>
            `;

            fijiNews.forEach((article, index) => {
                html += `
                    <div style="padding: 12px 0; border-bottom: 1px solid #f3f4f6; ${index === fijiNews.length - 1 ? 'border-bottom: none;' : ''}">
                        <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center; flex-wrap: wrap;">
                            <span style="font-size: 11px; font-weight: 600; color: #06B6D4; background: #cffafe; padding: 2px 8px; border-radius: 12px;">🏝️ Fiji</span>
                            <span style="font-size: 11px; color: #9CA3AF;">${article.timeAgo}</span>
                        </div>
                        <h4 style="margin: 0 0 4px 0; font-size: 13px; color: #0F172A; font-weight: 600; line-height: 1.3;">
                            ${article.title}
                        </h4>
                        <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; line-height: 1.3;">
                            ${article.description}
                        </p>
                        <a href="${article.url}" target="_blank" style="font-size: 11px; color: #06B6D4; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                            Read More →
                        </a>
                    </div>
                `;
            });

            html += `
                <div style="padding: 12px; text-align: center; border-top: 1px solid #f3f4f6; background: #f9fafb; border-radius: 0 0 12px 12px; font-size: 11px; color: #9CA3AF; margin-top: 12px;">
                    <a href="https://www.fijivillage.com/news/" target="_blank" style="color: #06B6D4; text-decoration: none;">📰 Visit Fiji Village News</a>
                </div>
            `;

            document.getElementById('news-content').innerHTML = html;
        }





        function getTimeAgo(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'just now';
            if (diffMins < 60) return `${diffMins} min ago`;
            if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
            return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
        }


        function loadWeatherData() {
            const content = document.getElementById('weather-content');
            content.innerHTML = '<div style="font-size: 14px; margin-bottom: 10px;">Loading weather data...</div>';

            // Using Open-Meteo API (free, no API key needed)
            // Get user's approximate location (will default to a location)
            fetch('https://api.open-meteo.com/v1/forecast?latitude=35.6762&longitude=139.6503&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&daily=temperature_2m_max,temperature_2m_min,weather_code&temperature_unit=celsius&timezone=auto', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.current) {
                    const current = data.current;
                    const daily = data.daily;
                    
                    // Weather description mapping
                    const weatherDesc = {
                        0: '☀️ Clear',
                        1: '🌤️ Partly Cloudy',
                        2: '☁️ Cloudy',
                        3: '☁️ Overcast',
                        45: '🌫️ Foggy',
                        48: '🌫️ Foggy',
                        51: '🌧️ Light Drizzle',
                        53: '🌧️ Moderate Drizzle',
                        55: '🌧️ Heavy Drizzle',
                        61: '🌧️ Slight Rain',
                        63: '🌧️ Moderate Rain',
                        65: '⛈️ Heavy Rain',
                        71: '🌨️ Light Snow',
                        73: '🌨️ Moderate Snow',
                        75: '🌨️ Heavy Snow',
                        77: '🌨️ Snow Grains',
                        80: '🌧️ Slight Showers',
                        81: '🌧️ Moderate Showers',
                        82: '⛈️ Violent Showers',
                        85: '🌨️ Light Snow Showers',
                        86: '🌨️ Heavy Snow Showers',
                        95: '⛈️ Thunderstorm',
                        96: '⛈️ Thunderstorm with Hail',
                        99: '⛈️ Thunderstorm with Heavy Hail'
                    };
                    
                    const weather = weatherDesc[current.weather_code] || '🌡️ Unknown';
                    
                    let html = `
                        <div style="padding: 16px; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 8px;">${weather}</div>
                            <div style="font-size: 24px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">
                                ${current.temperature_2m}°C
                            </div>
                            <div style="font-size: 13px; color: #6B7280; margin-bottom: 12px;">
                                Humidity: ${current.relative_humidity_2m}%
                            </div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 16px;">
                                Wind: ${current.wind_speed_10m} km/h
                            </div>
                            
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 12px;">
                                <div style="font-weight: 600; color: #0F172A; margin-bottom: 8px; font-size: 12px;">3-Day Forecast</div>
                    `;
                    
                    // Add 3-day forecast
                    for (let i = 0; i < 3; i++) {
                        const date = new Date(daily.time[i]);
                        const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
                        const maxTemp = daily.temperature_2m_max[i];
                        const minTemp = daily.temperature_2m_min[i];
                        const weatherCode = daily.weather_code[i];
                        const weatherEmoji = weatherDesc[weatherCode] || '🌡️';
                        
                        html += `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 12px;">
                                <div style="text-align: left; flex: 1;">${dayName}</div>
                                <div>${weatherEmoji}</div>
                                <div style="text-align: right; min-width: 50px; color: #0F172A; font-weight: 600;">${maxTemp}°/${minTemp}°</div>
                            </div>
                        `;
                    }
                    
                    html += `
                            </div>
                        </div>
                        <div style="padding: 8px 16px; text-align: center; border-top: 1px solid #f3f4f6; background: #f9fafb; border-radius: 0 0 12px 12px; font-size: 11px; color: #9CA3AF;">
                            Data from Open-Meteo API
                        </div>
                    `;
                    
                    content.innerHTML = html;
                } else {
                    content.innerHTML = '<div style="color: #DC2626; font-size: 12px;">Unable to load weather data</div>';
                }
            })
            .catch(error => {
                console.error('Weather error:', error);
                content.innerHTML = '<div style="color: #DC2626; font-size: 12px;">Weather service unavailable</div>';
            });
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
            const weatherBell = document.getElementById('weather-bell');
            const weatherDropdown = document.getElementById('weather-dropdown');
            const newsBell = document.getElementById('news-bell');
            const newsDropdown = document.getElementById('news-dropdown');
            
            if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
            
            if (!weatherBell.contains(event.target) && !weatherDropdown.contains(event.target)) {
                weatherDropdown.classList.remove('active');
            }
            
            if (!newsBell.contains(event.target) && !newsDropdown.contains(event.target)) {
                newsDropdown.classList.remove('active');
            }
        });

        // Load notifications on page load
        window.addEventListener('load', function() {
            loadNotifications();
            startNewsAutoRefresh();
        });
    </script>

    @yield('scripts')
</body>
</html>
