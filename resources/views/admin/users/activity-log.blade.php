@extends('layouts.app')

@section('page-title', 'Activity Log - ' . $user->name)

@section('content')
<style>
    .activity-header {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        padding: 30px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .activity-header h1 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 700;
    }

    .activity-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #2563EB;
    }

    .stat-label {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
    }

    .activity-breakdown {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .breakdown-title {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .breakdown-item:last-child {
        border-bottom: none;
    }

    .breakdown-label {
        font-size: 13px;
        color: #0F172A;
        font-weight: 500;
    }

    .breakdown-count {
        font-size: 18px;
        font-weight: 700;
        color: #2563EB;
    }

    .activity-list {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .activity-list-title {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        padding: 20px;
        border-bottom: 2px solid #f3f4f6;
    }

    .activity-entry {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        gap: 15px;
        transition: background-color 0.2s;
    }

    .activity-entry:hover {
        background-color: #f9fafb;
    }

    .activity-entry:last-child {
        border-bottom: none;
    }

    .activity-icon {
        font-size: 24px;
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        border-radius: 6px;
    }

    .activity-details {
        flex: 1;
    }

    .activity-action {
        font-size: 13px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .activity-description {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 4px;
    }

    .activity-timestamp {
        font-size: 11px;
        color: #9CA3AF;
    }

    .activity-meta {
        display: flex;
        gap: 20px;
        font-size: 11px;
        color: #6B7280;
        margin-top: 4px;
    }

    .pagination-wrapper {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background: #f3f4f6;
        color: #0F172A;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .back-button:hover {
        background: #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6B7280;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-state-text {
        font-size: 16px;
        margin-bottom: 8px;
    }
</style>

<a href="{{ route('admin.users.profile', $user) }}" class="back-button">← Back to Profile</a>

<!-- Header -->
<div class="activity-header">
    <h1>📊 Activity Log</h1>
    <p>{{ $user->name }} - Complete audit trail of all actions</p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Activities</div>
        <div class="stat-value">{{ $stats['total_activities'] }}</div>
    </div>
    <div class="stat-card" style="border-left-color: #06B6D4;">
        <div class="stat-label">This Month</div>
        <div class="stat-value" style="color: #06B6D4;">{{ $stats['this_month'] }}</div>
    </div>
    <div class="stat-card" style="border-left-color: #16A34A;">
        <div class="stat-label">This Week</div>
        <div class="stat-value" style="color: #16A34A;">{{ $stats['this_week'] }}</div>
    </div>
</div>

<!-- Activity Breakdown -->
@if($activityBreakdown->count() > 0)
    <div class="activity-breakdown">
        <div class="breakdown-title">Activity Breakdown by Type</div>
        @foreach($activityBreakdown as $breakdown)
            <div class="breakdown-item">
                <span class="breakdown-label">
                    @switch($breakdown->action_type)
                        @case('login')
                            🔓 Login
                            @break
                        @case('time_entry_created')
                            ⏱️ Time Entry Created
                            @break
                        @case('project_accessed')
                            📁 Project Accessed
                            @break
                        @case('profile_updated')
                            ✏️ Profile Updated
                            @break
                        @default
                            📌 {{ ucfirst(str_replace('_', ' ', $breakdown->action_type)) }}
                    @endswitch
                </span>
                <span class="breakdown-count">{{ $breakdown->count }}</span>
            </div>
        @endforeach
    </div>
@endif

<!-- Activity List -->
<div class="activity-list">
    <div class="activity-list-title">📋 Activity Timeline</div>
    
    @if($activities->count() > 0)
        @foreach($activities as $activity)
            <div class="activity-entry">
                <div class="activity-icon">
                    @switch($activity->action_type)
                        @case('login')
                            🔓
                            @break
                        @case('time_entry_created')
                            ⏱️
                            @break
                        @case('project_accessed')
                            📁
                            @break
                        @case('profile_updated')
                            ✏️
                            @break
                        @default
                            📌
                    @endswitch
                </div>
                <div class="activity-details">
                    <div class="activity-action">
                        @switch($activity->action_type)
                            @case('login')
                                User Login
                                @break
                            @case('time_entry_created')
                                Time Entry Created
                                @break
                            @case('project_accessed')
                                Project Accessed
                                @break
                            @case('profile_updated')
                                Profile Updated
                                @break
                            @default
                                {{ ucfirst(str_replace('_', ' ', $activity->action_type)) }}
                        @endswitch
                    </div>
                    <div class="activity-description">{{ $activity->description }}</div>
                    <div class="activity-timestamp">{{ $activity->created_at->format('M d, Y H:i:s') }}</div>
                    @if($activity->model_type)
                        <div class="activity-meta">
                            <span>Type: {{ $activity->model_type }}</span>
                            @if($activity->model_id)
                                <span>ID: {{ $activity->model_id }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="pagination-wrapper" style="padding: 20px;">
                {{ $activities->links('vendor.pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <div class="empty-state-text">No activities recorded</div>
            <p style="font-size: 13px;">This user hasn't performed any tracked actions yet.</p>
        </div>
    @endif
</div>

@endsection
