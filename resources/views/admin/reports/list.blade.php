@extends('layouts.app')

@section('page-title', 'My Reports')

@section('content')
<style>
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
    }

    .header-left p {
        color: #6B7280;
        font-size: 14px;
        margin: 0;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .report-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
        font-size: 32px;
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

    .report-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    .report-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .report-actions a,
    .report-actions button {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .btn-view {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-view:hover {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #DC2626 0%, #b91c1c 100%);
        color: white;
    }

    .btn-delete:hover {
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
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
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 32px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        text-decoration: none;
        color: #0F172A;
        font-size: 13px;
        font-weight: 600;
    }

    .pagination a:hover {
        background: #f9fafb;
    }

    .pagination .active {
        background: #2563EB;
        color: white;
        border-color: #2563EB;
    }
</style>

<!-- Header Section -->
<div class="header-section">
    <div class="header-content">
        <div class="header-left">
            <h1>📋 My Reports</h1>
            <p>View and manage your custom reports</p>
        </div>
        <a href="{{ route('admin.reports.builder') }}" class="btn btn-primary">+ Create New Report</a>
    </div>
</div>

<!-- Reports Grid -->
@if($customReports->count() > 0)
    <div class="reports-grid">
        @foreach($customReports as $report)
            <div class="report-card">
                <div class="report-icon">📊</div>
                <div class="report-title">{{ $report->name }}</div>
                @if($report->description)
                    <div class="report-description">{{ $report->description }}</div>
                @else
                    <div class="report-description" style="color: #d1d5db;">No description provided</div>
                @endif
                
                <div class="report-meta">
                    <span>Created by {{ $report->createdBy->name ?? 'Unknown' }}</span>
                    <span>{{ $report->created_at->format('M d, Y') }}</span>
                </div>

                <div class="report-actions">
                    <a href="{{ route('admin.reports.show', $report) }}" class="btn-view">👁️ View</a>
                    <form method="POST" action="{{ route('admin.reports.delete-custom', $report) }}" style="flex: 1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">🗑️ Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($customReports->hasPages())
        <div class="pagination">
            @if($customReports->onFirstPage())
                <span>← Previous</span>
            @else
                <a href="{{ $customReports->previousPageUrl() }}">← Previous</a>
            @endif

            @foreach($customReports->getUrlRange(1, $customReports->lastPage()) as $page => $url)
                @if($page == $customReports->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($customReports->hasMorePages())
                <a href="{{ $customReports->nextPageUrl() }}">Next →</a>
            @else
                <span>Next →</span>
            @endif
        </div>
    @endif
@else
    <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3>No Reports Yet</h3>
        <p>You haven't created any custom reports yet. Start by creating your first report.</p>
        <a href="{{ route('admin.reports.builder') }}" class="btn btn-primary">+ Create New Report</a>
    </div>
@endif

@endsection
