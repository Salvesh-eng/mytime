@extends('layouts.app')

@section('page-title', 'Export Data')

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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f0fdf4 0%, #f0f9ff 50%, #fdf2f8 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .header-section {
        display: none;
    }

    .header-section h1 {
        display: none;
    }

    .header-section p {
        display: none;
    }

    /* Filter Section */
    .filter-section {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-mint) 100%);
        border-radius: 16px;
        border: 2px solid rgba(179, 255, 179, 0.5);
        padding: 20px;
        margin-bottom: 32px;
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.25);
    }

    .filter-title {
        font-size: 14px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 0;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 11px;
        font-weight: 700;
        color: #0F172A;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        padding: 10px 12px;
        border: 2px solid rgba(179, 217, 255, 0.4);
        border-radius: 8px;
        font-size: 12px;
        background: white;
        color: #0F172A;
        transition: all 0.3s;
        font-weight: 500;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.05) 0%, rgba(179, 217, 255, 0.05) 100%);
    }

    /* Section Titles */
    .section-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(179, 255, 217, 0.15) 100%);
        border-radius: 12px;
        border-left: 4px solid;
    }

    .section-title.time-entries {
        border-left-color: #06B6D4;
    }

    .section-title.projects {
        border-left-color: #8B5CF6;
    }

    .section-title.users {
        border-left-color: #EC4899;
    }

    .section-title.financial {
        border-left-color: #10B981;
    }

    .section-icon {
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
    }

    .section-title.projects .section-icon {
        background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%);
    }

    .section-title.users .section-icon {
        background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%);
    }

    .section-title.financial .section-icon {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
    }

    /* Export Grid */
    .export-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }

    .export-card {
        background: linear-gradient(135deg, var(--soft-lavender) 0%, var(--soft-mint) 100%);
        border-radius: 12px;
        border: 2px solid rgba(179, 255, 179, 0.5);
        padding: 16px;
        box-shadow: 0 4px 16px rgba(179, 217, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .export-card:hover {
        box-shadow: 0 12px 32px rgba(179, 217, 255, 0.35);
        transform: translateY(-4px);
        border-color: rgba(179, 255, 179, 0.8);
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-mint) 100%);
    }

    .export-icon {
        font-size: 32px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(179, 255, 179, 0.2) 0%, rgba(179, 217, 255, 0.2) 100%);
    }

    .export-title {
        font-size: 13px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 6px;
    }

    .export-description {
        font-size: 11px;
        color: #475569;
        margin-bottom: 10px;
        flex: 1;
        line-height: 1.4;
    }

    .export-formats {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }

    .format-badge {
        display: inline-block;
        padding: 3px 8px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        border: 1px solid rgba(37, 99, 235, 0.3);
        border-radius: 5px;
        font-size: 9px;
        font-weight: 600;
        color: #2563EB;
    }

    .export-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .export-btn {
        flex: 1;
        padding: 10px 12px;
        border: none;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-width: 70px;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        color: #0F172A;
        border: 2px solid;
    }

    .export-csv {
        background: linear-gradient(135deg, var(--soft-orange) 0%, #FFD9B3 100%);
        border-color: rgba(255, 179, 179, 0.6);
        box-shadow: 0 4px 12px rgba(255, 179, 179, 0.3);
    }

    .export-csv:hover {
        box-shadow: 0 8px 20px rgba(255, 179, 179, 0.5);
        transform: translateY(-2px);
        background: linear-gradient(135deg, #FFD9B3 0%, var(--soft-orange) 100%);
        border-color: rgba(255, 179, 179, 0.8);
    }

    .export-excel {
        background: linear-gradient(135deg, var(--soft-green) 0%, #B3FFB3 100%);
        border-color: rgba(179, 255, 179, 0.6);
        box-shadow: 0 4px 12px rgba(179, 255, 179, 0.3);
    }

    .export-excel:hover {
        box-shadow: 0 8px 20px rgba(179, 255, 179, 0.5);
        transform: translateY(-2px);
        background: linear-gradient(135deg, #B3FFB3 0%, var(--soft-green) 100%);
        border-color: rgba(179, 255, 179, 0.8);
    }

    .export-pdf {
        background: linear-gradient(135deg, var(--soft-pink) 0%, #FFB3D9 100%);
        border-color: rgba(255, 179, 217, 0.6);
        box-shadow: 0 4px 12px rgba(255, 179, 217, 0.3);
    }

    .export-pdf:hover {
        box-shadow: 0 8px 20px rgba(255, 179, 217, 0.5);
        transform: translateY(-2px);
        background: linear-gradient(135deg, #FFB3D9 0%, var(--soft-pink) 100%);
        border-color: rgba(255, 179, 217, 0.8);
    }

    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.15) 0%, rgba(179, 217, 255, 0.15) 100%);
        border-radius: 16px;
        border: 2px solid rgba(179, 255, 179, 0.3);
        padding: 24px;
        margin-bottom: 48px;
        font-size: 14px;
        color: #0F172A;
        line-height: 1.8;
    }

    .info-box strong {
        color: #0F172A;
        font-weight: 700;
    }

    /* Toast Notifications */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease-out, slideOut 0.3s ease-out 3.7s forwards;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 400px;
        border: 2px solid;
    }

    .toast-success {
        background: linear-gradient(135deg, #16A34A 0%, #15803d 100%);
        color: white;
        border-color: #15803d;
    }

    .toast-error {
        background: linear-gradient(135deg, #DC2626 0%, #b91c1c 100%);
        color: white;
        border-color: #b91c1c;
    }

    .toast-info {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        border-color: #1d4ed8;
    }

    .toast-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 20px;
        padding: 0;
        margin-left: 8px;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .toast-close:hover {
        opacity: 1;
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

    /* Responsive */
    @media (max-width: 768px) {
        .header-section {
            padding: 24px;
            margin-bottom: 32px;
        }

        .header-section h1 {
            font-size: 28px;
        }

        .section-title {
            font-size: 20px;
            padding: 16px;
        }

        .export-grid {
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }

        .filter-section {
            padding: 20px;
        }

        .filter-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }
</style>

<!-- Header Section -->
<div class="header-section">
    <h1>📊 Export Your Data</h1>
    <p>Download your data in multiple formats for analysis, reporting, and archiving. Select your date range and choose your preferred export format.</p>
</div>

<!-- Info Box -->
<div class="info-box">
    <strong>💡 How to Export:</strong> Select your desired date range using the filters below, then click on any export button to download your data. All exports include the latest information from your system and are ready for use in spreadsheets, presentations, or archival purposes.
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="filter-title">
        <span>🔍</span>
        Filter Your Export
    </div>
    <form id="exportForm">
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
                <label>Data Type</label>
                <select id="dataType">
                    <option value="time-entries">Time Entries</option>
                    <option value="projects">Projects</option>
                    <option value="users">Users</option>
                    <option value="all">All Data</option>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Time Entries Export Section -->
<div style="margin-bottom: 28px;">
    <div class="section-title time-entries">
        <div class="section-icon">⏱️</div>
        <span>Time Entries Export</span>
    </div>

    <div class="export-grid">
        <div class="export-card">
            <div class="export-icon">📋</div>
            <div class="export-title">Time Entries Data</div>
            <div class="export-description">
                Export all time entries with dates, hours, projects, and status information in your preferred format.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('time-entries', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('time-entries', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('time-entries', 'pdf')">PDF</button>
            </div>
        </div>
    </div>
</div>

<!-- Projects Export Section -->
<div style="margin-bottom: 28px;">
    <div class="section-title projects">
        <div class="section-icon">📁</div>
        <span>Projects Export</span>
    </div>

    <div class="export-grid">
        <div class="export-card">
            <div class="export-icon">🎯</div>
            <div class="export-title">Projects Data</div>
            <div class="export-description">
                Export all projects with status, progress, timelines, budgets, and team member information.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('projects', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('projects', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('projects', 'pdf')">PDF</button>
            </div>
        </div>
    </div>
</div>

<!-- Users Export Section -->
<div style="margin-bottom: 28px;">
    <div class="section-title users">
        <div class="section-icon">👥</div>
        <span>Users Export</span>
    </div>

    <div class="export-grid">
        <div class="export-card">
            <div class="export-icon">👤</div>
            <div class="export-title">Users Data</div>
            <div class="export-description">
                Export all users with roles, contact information, status, and account details.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('users', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('users', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('users', 'pdf')">PDF</button>
            </div>
        </div>
    </div>
</div>

<!-- Financial Export Section -->
<div style="margin-bottom: 28px;">
    <div class="section-title financial">
        <div class="section-icon">💰</div>
        <span>Financial Export</span>
    </div>

    <div class="export-grid">
        <div class="export-card">
            <div class="export-icon">💳</div>
            <div class="export-title">Transactions Data</div>
            <div class="export-description">
                Export all financial transactions including income, expenses, and transfers with details.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('transactions', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('transactions', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('transactions', 'pdf')">PDF</button>
            </div>
        </div>

        <div class="export-card">
            <div class="export-icon">📊</div>
            <div class="export-title">Budgets Data</div>
            <div class="export-description">
                Export all budgets with allocated amounts, spent amounts, and budget status information.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('budgets', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('budgets', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('budgets', 'pdf')">PDF</button>
            </div>
        </div>

        <div class="export-card">
            <div class="export-icon">📄</div>
            <div class="export-title">Invoices Data</div>
            <div class="export-description">
                Export all invoices with amounts, dates, status, and payment information.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('invoices', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('invoices', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('invoices', 'pdf')">PDF</button>
            </div>
        </div>

        <div class="export-card">
            <div class="export-icon">🎯</div>
            <div class="export-title">Financial Summary</div>
            <div class="export-description">
                Export comprehensive financial summary with income, expenses, savings, and analysis.
            </div>
            <div class="export-formats">
                <span class="format-badge">CSV</span>
                <span class="format-badge">Excel</span>
                <span class="format-badge">PDF</span>
            </div>
            <div class="export-buttons">
                <button class="export-btn export-csv" onclick="exportData('financial-summary', 'csv')">CSV</button>
                <button class="export-btn export-excel" onclick="exportData('financial-summary', 'excel')">Excel</button>
                <button class="export-btn export-pdf" onclick="exportData('financial-summary', 'pdf')">PDF</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function exportData(dataType, format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (!startDate || !endDate) {
            showToast('Please select both start and end dates', 'error');
            return;
        }

        const params = new URLSearchParams({
            data_type: dataType,
            format: format,
            start_date: startDate,
            end_date: endDate
        });

        showToast(`Generating ${format.toUpperCase()} export...`, 'info');

        const url = `/admin/reports/export-csv?${params}`;
        const link = document.createElement('a');
        link.href = url;
        link.download = `export_${dataType}_${new Date().getTime()}.${getFileExtension(format)}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        setTimeout(() => {
            showToast(`${format.toUpperCase()} export downloaded successfully!`, 'success');
        }, 500);
    }

    function getFileExtension(format) {
        const extensions = {
            'csv': 'csv',
            'excel': 'xlsx',
            'pdf': 'pdf'
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
</script>
@endsection
