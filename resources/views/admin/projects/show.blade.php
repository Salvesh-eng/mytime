@extends('layouts.app')

@section('page-title', 'Project Dashboard')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .stat-card h3 {
        margin: 0 0 10px 0;
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .value {
        font-size: 32px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .section h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
        color: #0F172A;
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 12px;
    }

    .milestone {
        padding: 12px;
        background: #f9fafb;
        border-left: 3px solid #2563EB;
        border-radius: 4px;
        margin-bottom: 12px;
    }

    .milestone.completed {
        border-left-color: #16A34A;
        background: #f0fdf4;
    }

    .milestone.overdue {
        border-left-color: #DC2626;
        background: #fef2f2;
    }

    .milestone h4 {
        margin: 0 0 4px 0;
        font-size: 14px;
        color: #0F172A;
    }

    .milestone p {
        margin: 0;
        font-size: 12px;
        color: #6B7280;
    }

    .time-entry {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #f9fafb;
        border-radius: 4px;
        margin-bottom: 8px;
        border-left: 3px solid #06B6D4;
    }

    .client-info {
        background: #f0f9ff;
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid #06B6D4;
    }

    .action-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .action-bar a, .action-bar button {
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }
</style>

<div class="dashboard-header">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="margin: 0 0 10px 0; font-size: 32px;">{{ $project->name }}</h1>
            <p style="margin: 0; opacity: 0.9;">{{ $project->description }}</p>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 28px; font-weight: 700;">{{ $stats['total_hours_logged'] ?? 0 }} hrs</div>
            <div style="font-size: 12px; opacity: 0.9;">Total Logged</div>
        </div>
    </div>
</div>

<div class="action-bar">
    <a href="{{ route('admin.projects.edit', $project) }}" style="background: #2563EB; color: white;">✏️ Edit Project</a>
    <form method="POST" action="{{ route('admin.projects.archive', $project) }}" style="display: inline;">
        @csrf
        <button type="submit" style="background: #F59E0B; color: white;">📦 Archive</button>
    </form>
    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" style="display: inline;" onsubmit="return confirm('Permanently delete this project?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="background: #DC2626; color: white;">🗑️ Delete</button>
    </form>
</div>

<!-- Statistics Grid -->
<div class="dashboard-grid">
    <div class="stat-card">
        <h3>Progress</h3>
        <div class="value" style="color: #06B6D4;">{{ $stats['total_hours_logged'] ?? 0 }}%</div>
        <div style="font-size: 12px; color: #6B7280;">of estimated hours</div>
    </div>

    <div class="stat-card">
        <h3>Team Members</h3>
        <div class="value" style="color: #2563EB;">{{ $stats['total_team_members'] }}</div>
        <div style="font-size: 12px; color: #6B7280;">assigned to project</div>
    </div>

    <div class="stat-card">
        <h3>Milestones</h3>
        <div class="value" style="color: #16A34A;">{{ $stats['completed_milestones'] }}/{{ $stats['total_milestones'] }}</div>
        <div style="font-size: 12px; color: #6B7280;">completed</div>
    </div>

    @if($project->budget)
        <div class="stat-card">
            <h3>Budget</h3>
            <div class="value" style="color: #F59E0B;">${{ number_format($stats['remaining_budget'], 0) }}</div>
            <div style="font-size: 12px; color: #6B7280;">remaining</div>
        </div>
    @endif
</div>

<!-- Client Information -->
@if($project->client)
    <div class="section">
        <h2>👤 Client Information</h2>
        <div class="client-info">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <div style="font-size: 12px; color: #6B7280;">Client Name</div>
                    <div style="font-weight: 600;">{{ $project->client->client_name }}</div>
                </div>
                @if($project->client->contact_person)
                    <div>
                        <div style="font-size: 12px; color: #6B7280;">Contact Person</div>
                        <div style="font-weight: 600;">{{ $project->client->contact_person }}</div>
                    </div>
                @endif
                @if($project->client->email)
                    <div>
                        <div style="font-size: 12px; color: #6B7280;">Email</div>
                        <div style="font-weight: 600;"><a href="mailto:{{ $project->client->email }}" style="color: #2563EB;">{{ $project->client->email }}</a></div>
                    </div>
                @endif
                @if($project->client->phone)
                    <div>
                        <div style="font-size: 12px; color: #6B7280;">Phone</div>
                        <div style="font-weight: 600;">{{ $project->client->phone }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Milestones -->
<div class="section">
    <h2>🎯 Upcoming Milestones</h2>
    @forelse($upcomingMilestones as $milestone)
        <div class="milestone @if($milestone->status === 'completed') completed @elseif($milestone->is_overdue) overdue @endif">
            <h4>{{ $milestone->title }}</h4>
            <p>📅 Target: {{ $milestone->target_date->format('M d, Y') }} • Progress: {{ $milestone->completion_percentage }}%</p>
        </div>
    @empty
        <p style="color: #6B7280;">No upcoming milestones. <a href="{{ route('admin.projects.edit', $project) }}">Add one →</a></p>
    @endforelse
</div>

<!-- Recent Time Entries -->
<div class="section">
    <h2>⏱️ Recent Time Entries</h2>
    @forelse($recentTimeEntries as $entry)
        <div class="time-entry">
            <div>
                <div style="font-weight: 600; color: #0F172A;">{{ $entry->user->name }}</div>
                <div style="font-size: 12px; color: #6B7280;">{{ $entry->entry_date->format('M d, Y') }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 600; color: #06B6D4;">{{ round($entry->duration_minutes / 60, 1) }} hrs</div>
            </div>
        </div>
    @empty
        <p style="color: #6B7280;">No time entries yet.</p>
    @endforelse
</div>

@endsection
