@extends('layouts.app')

@section('page-title', 'Reports & Exports')

@section('content')
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

    .filter-section {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select {
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .filter-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: white;
        color: #0F172A;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #2563EB;
    }

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .report-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .report-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
        border-color: #2563EB;
    }

    .report-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }

    .report-title {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .report-description {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 16px;
        flex: 1;
        line-height: 1.5;
    }

    .report-formats {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .format-badge {
        display: inline-block;
        padding: 4px 10px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        border: 1px solid rgba(37, 99, 235, 0.2);
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #2563EB;
    }

    .report-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .export-btn {
        flex: 1;
        padding: 10px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 80px;
    }

    .export-pdf {
        background: linear-gradient(135deg, #DC2626 0%, #b91c1c 100%);
        color: white;
    }

    .export-pdf:hover {
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        transform: translateY(-2px);
    }

    .export-excel {
        background: linear-gradient(135deg, #16A34A 0%, #15803d 100%);
        color: white;
    }

    .export-excel:hover {
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        transform: translateY(-2px);
    }

    .export-word {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
    }

    .export-word:hover {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
    }

    .export-csv {
        background: linear-gradient(135deg, #F59E0B 0%, #d97706 100%);
        color: white;
    }

    .export-csv:hover {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        transform: translateY(-2px);
    }

    .export-json {
        background: linear-gradient(135deg, #8B5CF6 0%, #7c3aed 100%);
        color: white;
    }

    .export-json:hover {
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        transform: translateY(-2px);
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-icon {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        padding: 10px;
        border-radius: 10px;
        font-size: 20px;
    }

    .info-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #0F172A;
        line-height: 1.6;
    }

    .info-box strong {
        color: #0F172A;
        font-weight: 600;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-item {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .stat-label {
        font-size: 11px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #2563EB;
    }

    .no-data {
        text-align: center;
        padding: 48px 20px;
        background: linear-gradient(135deg, #f0f9ff 0%, #fef3c7 100%);
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .no-data-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .no-data h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .no-data p {
        color: #6B7280;
        font-size: 14px;
        margin: 0;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .reports-grid {
            grid-template-columns: 1fr;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Header Section -->
<div class="header-section">
    <div class="header-content">
        <div class="header-left">
            <h1>📊 Reports & Exports</h1>
            <p>Generate and export your data in multiple formats</p>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="filter-row">
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" id="startDate" value="{{ date('Y-m-01') }}">
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" id="endDate" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
            <label>Report Type</label>
            <select id="reportType">
                <option value="">All Reports</option>
                <option value="time-entries">Time Entries</option>
                <option value="projects">Projects</option>
                <option value="summary">Summary</option>
            </select>
        </div>
    </div>
    <div class="filter-buttons">
        <button class="btn btn-primary" onclick="applyFilters()">🔍 Apply Filters</button>
        <button class="btn btn-secondary" onclick="resetFilters()">↻ Reset</button>
    </div>
</div>

<!-- Info Box -->
<div class="info-box">
    <strong>💡 Tip:</strong> Select your desired date range and report type, then choose your preferred export format. All exports include the latest data from your account.
</div>

<!-- Statistics -->
<div class="stats-row">
    <div class="stat-item">
        <div class="stat-label">Total Hours</div>
        <div class="stat-value" id="totalHours">0</div>
    </div>
    <div class="stat-item">
        <div class="stat-label">Projects</div>
        <div class="stat-value" id="totalProjects">0</div>
    </div>
    <div class="stat-item">
        <div class="stat-label">Time Entries</div>
        <div class="stat-value" id="totalEntries">0</div>
    </div>
    <div class="stat-item">
        <div class="stat-label">Date Range</div>
        <div class="stat-value" style="font-size: 14px;" id="dateRange">-</div>
    </div>
</div>

<!-- Time Entries Reports -->
<div style="margin-bottom: 40px;">
    <h2 class="section-title">
        <span class="section-icon">⏱️</span>
        Time Entries Reports
    </h2>

    <div class="reports-grid">
        <!-- Time Entries Summary -->
        <div class="report-card">
            <div class="report-icon">📋</div>
            <div class="report-title">Time Entries Summary</div>
            <div class="report-description">
                Comprehensive summary of all time entries with daily totals, project breakdown, and productivity metrics.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">CSV</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('time-entries-summary', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('time-entries-summary', 'excel')">📊 Excel</button>
                <button class="export-btn export-csv" onclick="exportReport('time-entries-summary', 'csv')">📑 CSV</button>
            </div>
        </div>

        <!-- Detailed Time Log -->
        <div class="report-card">
            <div class="report-icon">📝</div>
            <div class="report-title">Detailed Time Log</div>
            <div class="report-description">
                Complete log of all time entries with start/end times, duration, project assignment, and status.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">Word</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('detailed-time-log', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('detailed-time-log', 'excel')">📊 Excel</button>
                <button class="export-btn export-word" onclick="exportReport('detailed-time-log', 'word')">📘 Word</button>
            </div>
        </div>

        <!-- Weekly Report -->
        <div class="report-card">
            <div class="report-icon">📅</div>
            <div class="report-title">Weekly Report</div>
            <div class="report-description">
                Weekly breakdown of hours logged, projects worked on, and productivity trends for each week.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">JSON</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('weekly-report', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('weekly-report', 'excel')">📊 Excel</button>
                <button class="export-btn export-json" onclick="exportReport('weekly-report', 'json')">{ } JSON</button>
            </div>
        </div>

        <!-- Monthly Report -->
        <div class="report-card">
            <div class="report-icon">📊</div>
            <div class="report-title">Monthly Report</div>
            <div class="report-description">
                Monthly summary with total hours, project distribution, daily averages, and performance metrics.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">CSV</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('monthly-report', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('monthly-report', 'excel')">📊 Excel</button>
                <button class="export-btn export-csv" onclick="exportReport('monthly-report', 'csv')">📑 CSV</button>
            </div>
        </div>
    </div>
</div>

<!-- Project Reports -->
<div style="margin-bottom: 40px;">
    <h2 class="section-title">
        <span class="section-icon">📁</span>
        Project Reports
    </h2>

    <div class="reports-grid">
        <!-- Project Overview -->
        <div class="report-card">
            <div class="report-icon">🎯</div>
            <div class="report-title">Project Overview</div>
            <div class="report-description">
                Complete overview of all projects including status, progress, team members, and timelines.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">Word</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('project-overview', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('project-overview', 'excel')">📊 Excel</button>
                <button class="export-btn export-word" onclick="exportReport('project-overview', 'word')">📘 Word</button>
            </div>
        </div>

        <!-- Project Time Allocation -->
        <div class="report-card">
            <div class="report-icon">⏳</div>
            <div class="report-title">Time Allocation by Project</div>
            <div class="report-description">
                Detailed breakdown of hours spent on each project with percentages and comparative analysis.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">CSV</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('project-time-allocation', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('project-time-allocation', 'excel')">📊 Excel</button>
                <button class="export-btn export-csv" onclick="exportReport('project-time-allocation', 'csv')">📑 CSV</button>
            </div>
        </div>

        <!-- Project Status Report -->
        <div class="report-card">
            <div class="report-icon">✅</div>
            <div class="report-title">Project Status Report</div>
            <div class="report-description">
                Current status of all projects with completion percentage, milestones, and upcoming deadlines.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">JSON</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('project-status', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('project-status', 'excel')">📊 Excel</button>
                <button class="export-btn export-json" onclick="exportReport('project-status', 'json')">{ } JSON</button>
            </div>
        </div>

        <!-- Project Budget Report -->
        <div class="report-card">
            <div class="report-icon">💰</div>
            <div class="report-title">Project Budget Report</div>
            <div class="report-description">
                Budget analysis for each project including allocated budget, spent hours, and cost analysis.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">Word</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('project-budget', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('project-budget', 'excel')">📊 Excel</button>
                <button class="export-btn export-word" onclick="exportReport('project-budget', 'word')">📘 Word</button>
            </div>
        </div>
    </div>
</div>

<!-- Summary & Analytics Reports -->
<div style="margin-bottom: 40px;">
    <h2 class="section-title">
        <span class="section-icon">📈</span>
        Summary & Analytics
    </h2>

    <div class="reports-grid">
        <!-- Productivity Summary -->
        <div class="report-card">
            <div class="report-icon">🚀</div>
            <div class="report-title">Productivity Summary</div>
            <div class="report-description">
                Overall productivity metrics including average daily hours, peak productivity times, and trends.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">CSV</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('productivity-summary', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('productivity-summary', 'excel')">📊 Excel</button>
                <button class="export-btn export-csv" onclick="exportReport('productivity-summary', 'csv')">📑 CSV</button>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="report-card">
            <div class="report-icon">👥</div>
            <div class="report-title">Team Performance</div>
            <div class="report-description">
                Team member performance metrics, hours logged, project contributions, and comparative analysis.
            </div>
            <div class="report-formats">
                <span class="format-badge">PDF</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">Word</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-pdf" onclick="exportReport('team-performance', 'pdf')">📄 PDF</button>
                <button class="export-btn export-excel" onclick="exportReport('team-performance', 'excel')">📊 Excel</button>
                <button class="export-btn export-word" onclick="exportReport('team-performance', 'word')">📘 Word</button>
            </div>
        </div>

        <!-- Custom Data Export -->
        <div class="report-card">
            <div class="report-icon">⚙️</div>
            <div class="report-title">Custom Data Export</div>
            <div class="report-description">
                Export raw data in your preferred format for custom analysis, integration, or archival purposes.
            </div>
            <div class="report-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">JSON</span>
                <span class="format-badge">Excel</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-csv" onclick="exportReport('custom-export', 'csv')">📑 CSV</button>
                <button class="export-btn export-json" onclick="exportReport('custom-export', 'json')">{ } JSON</button>
                <button class="export-btn export-excel" onclick="exportReport('custom-export', 'excel')">📊 Excel</button>
            </div>
        </div>

        <!-- Full Data Backup -->
        <div class="report-card">
            <div class="report-icon">💾</div>
            <div class="report-title">Full Data Backup</div>
            <div class="report-description">
                Complete backup of all your data including time entries, projects, and metadata in JSON format.
            </div>
            <div class="report-formats">
                <span class="format-badge">JSON</span>
                <span class="format-badge">ZIP</span>
            </div>
            <div class="report-actions">
                <button class="export-btn export-json" onclick="exportReport('full-backup', 'json')">{ } JSON</button>
                <button class="export-btn export-csv" onclick="exportReport('full-backup', 'zip')">📦 ZIP</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function applyFilters() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const reportType = document.getElementById('reportType').value;

        // Update date range display
        const start = new Date(startDate);
        const end = new Date(endDate);
        const dateRangeText = `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;
        document.getElementById('dateRange').textContent = dateRangeText;

        // Fetch statistics
        fetchStatistics(startDate, endDate, reportType);

        // Show success message
        showToast('Filters applied successfully!', 'success');
    }

    function resetFilters() {
        document.getElementById('startDate').value = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        document.getElementById('endDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('reportType').value = '';
        
        applyFilters();
        showToast('Filters reset to default', 'info');
    }

    function fetchStatistics(startDate, endDate, reportType) {
        const params = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            report_type: reportType
        });

        fetch(`/api/user/report-statistics?${params}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalHours').textContent = (data.total_hours || 0).toFixed(2);
            document.getElementById('totalProjects').textContent = data.total_projects || 0;
            document.getElementById('totalEntries').textContent = data.total_entries || 0;
        })
        .catch(error => {
            console.error('Error fetching statistics:', error);
            showToast('Error loading statistics', 'error');
        });
    }

    function exportReport(reportType, format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        const params = new URLSearchParams({
            report_type: reportType,
            format: format,
            start_date: startDate,
            end_date: endDate
        });

        const url = `/api/user/export-report?${params}`;

        // Show loading state
        showToast(`Generating ${format.toUpperCase()} report...`, 'info');

        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Export failed');
            
            // Get filename from response headers or create one
            const contentDisposition = response.headers.get('content-disposition');
            let filename = `report_${reportType}_${new Date().getTime()}.${getFileExtension(format)}`;
            
            if (contentDisposition) {
                const filenameMatch = contentDisposition.match(/filename="?([^"]+)"?/);
                if (filenameMatch) filename = filenameMatch[1];
            }

            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);

            showToast(`${format.toUpperCase()} report downloaded successfully!`, 'success');
        })
        .catch(error => {
            console.error('Export error:', error);
            showToast(`Error generating ${format.toUpperCase()} report`, 'error');
        });
    }

    function getFileExtension(format) {
        const extensions = {
            'pdf': 'pdf',
            'excel': 'xlsx',
            'word': 'docx',
            'csv': 'csv',
            'json': 'json',
            'zip': 'zip'
        };
        return extensions[format] || format;
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <span>${type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ'}</span>
            <span>${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) toast.remove();
        }, 4000);
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        applyFilters();
    });
</script>

<style>
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease-out, slideOut 0.3s ease-out 3.7s forwards;
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

    .toast-info {
        background-color: #2563EB;
        color: white;
        border-left: 4px solid #1d4ed8;
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
@endsection
