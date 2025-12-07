@extends('layouts.app')

@section('page-title', 'User Profile - ' . $user->name)

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

    .profile-container {
        max-width: 100%;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(217, 179, 255, 0.2) 100%);
        color: #0c4a6e;
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
        border: 1px solid rgba(179, 217, 255, 0.3);
    }

    .back-button:hover {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.3) 0%, rgba(217, 179, 255, 0.3) 100%);
        transform: translateX(-3px);
    }

    /* Profile Header */
    .profile-header {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.25) 0%, rgba(217, 179, 255, 0.25) 50%, rgba(179, 255, 217, 0.2) 100%);
        padding: 40px;
        border-radius: 24px;
        margin-bottom: 30px;
        border: 1px solid rgba(179, 217, 255, 0.3);
        box-shadow: 0 8px 32px rgba(179, 217, 255, 0.2);
    }

    .profile-header-content {
        display: flex;
        gap: 30px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        font-weight: 700;
        color: #0c4a6e;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(179, 217, 255, 0.3);
        border: 4px solid white;
        overflow: hidden;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h1 {
        margin: 0 0 12px 0;
        font-size: 32px;
        font-weight: 700;
        color: #0F172A;
    }

    .profile-info p {
        margin: 6px 0;
        font-size: 14px;
        color: #4B5563;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .profile-badge.active {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
    }

    .profile-badge.inactive {
        background: linear-gradient(135deg, rgba(229, 231, 235, 0.5) 0%, rgba(209, 213, 219, 0.5) 100%);
        color: #6B7280;
    }

    .profile-badge.admin {
        background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%);
        color: #9a3412;
    }

    .profile-badge.user {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        color: #0c4a6e;
    }

    /* Metrics Grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .metric-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        text-align: center;
        transition: all 0.3s;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .metric-card.blue { border-top: 4px solid var(--soft-blue); }
    .metric-card.green { border-top: 4px solid var(--soft-green); }
    .metric-card.purple { border-top: 4px solid var(--soft-purple); }
    .metric-card.orange { border-top: 4px solid var(--soft-orange); }

    .metric-icon {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .metric-label {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .metric-value {
        font-size: 32px;
        font-weight: 700;
        color: #0F172A;
    }

    /* Section Cards */
    .section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 217, 255, 0.08) 100%);
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.1);
        margin-bottom: 24px;
        border: 1px solid rgba(179, 217, 255, 0.2);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid rgba(179, 217, 255, 0.2);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title-icon {
        padding: 8px;
        border-radius: 10px;
        font-size: 16px;
    }

    .section-title-icon.blue { background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%); }
    .section-title-icon.green { background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); }
    .section-title-icon.purple { background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); }
    .section-title-icon.orange { background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); }

    /* Hierarchy Items */
    .hierarchy-section {
        margin-bottom: 20px;
    }

    .hierarchy-label {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .hierarchy-item {
        padding: 14px 16px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(217, 179, 255, 0.1) 100%);
        border-radius: 12px;
        margin-bottom: 10px;
        border-left: 4px solid var(--soft-blue);
        transition: all 0.2s;
    }

    .hierarchy-item:hover {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(217, 179, 255, 0.2) 100%);
    }

    .hierarchy-item-name {
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .hierarchy-item-role {
        font-size: 12px;
        color: #6B7280;
    }

    /* Projects List */
    .projects-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
    }

    .project-item {
        padding: 18px;
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.15) 0%, rgba(179, 217, 255, 0.1) 100%);
        border-radius: 14px;
        border-left: 4px solid var(--soft-mint);
        transition: all 0.2s;
    }

    .project-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(179, 255, 217, 0.2);
    }

    .project-name {
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .project-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .project-status.completed {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
    }

    .project-status.in-progress {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        color: #0c4a6e;
    }

    .project-status.planning {
        background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%);
        color: #9a3412;
    }

    .project-status.on-hold {
        background: linear-gradient(135deg, rgba(229, 231, 235, 0.5) 0%, rgba(209, 213, 219, 0.5) 100%);
        color: #6B7280;
    }

    /* Activity Items */
    .activity-item {
        padding: 14px 16px;
        background: linear-gradient(135deg, rgba(179, 255, 179, 0.1) 0%, rgba(179, 217, 255, 0.1) 100%);
        border-radius: 12px;
        margin-bottom: 12px;
        border-left: 4px solid var(--soft-green);
        display: flex;
        gap: 14px;
        transition: all 0.2s;
    }

    .activity-item:hover {
        background: linear-gradient(135deg, rgba(179, 255, 179, 0.2) 0%, rgba(179, 217, 255, 0.2) 100%);
    }

    .activity-icon {
        font-size: 22px;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-description {
        font-size: 14px;
        color: #0F172A;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .activity-time {
        font-size: 12px;
        color: #6B7280;
    }

    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        color: #0c4a6e;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
        border: 1px solid rgba(179, 217, 255, 0.5);
    }

    .view-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(179, 217, 255, 0.3);
    }

    /* Bio Section */
    .bio-text {
        color: #4B5563;
        line-height: 1.7;
        font-size: 14px;
    }

    /* Empty State */
    .empty-text {
        color: #9CA3AF;
        text-align: center;
        padding: 24px;
        font-size: 14px;
    }
</style>

<div class="profile-container">
    <a href="{{ route('admin.users.index') }}" class="back-button">← Back to Users</a>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-photo">
                @if($user->photo_url)
                    <img src="{{ asset('storage/' . $user->photo_url) }}" alt="{{ $user->name }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p>📧 {{ $user->email }}</p>
                @if($user->phone)
                    <p>📱 {{ $user->phone }}</p>
                @endif
                @if($user->position)
                    <p>💼 {{ $user->position }}</p>
                @endif
                @if($user->department)
                    <p>🏢 {{ $user->department }}</p>
                @endif
                <div class="profile-badges">
                    <span class="profile-badge {{ $user->isActive() ? 'active' : 'inactive' }}">
                        @if($user->isActive())
                            ✅ Active
                        @else
                            ⏸️ Inactive
                        @endif
                    </span>
                    <span class="profile-badge {{ $user->isAdmin() ? 'admin' : 'user' }}">
                        @if($user->isAdmin())
                            👑 Administrator
                        @else
                            👤 User
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="metrics-grid">
        <div class="metric-card blue">
            <div class="metric-icon">⏱️</div>
            <div class="metric-label">Total Hours Logged</div>
            <div class="metric-value">{{ number_format($totalHours, 1) }}</div>
        </div>
        <div class="metric-card green">
            <div class="metric-icon">📅</div>
            <div class="metric-label">This Month</div>
            <div class="metric-value">{{ number_format($monthlyHours, 1) }}</div>
        </div>
        <div class="metric-card purple">
            <div class="metric-icon">⚙️</div>
            <div class="metric-label">Active Projects</div>
            <div class="metric-value">{{ $activeProjects }}</div>
        </div>
        <div class="metric-card orange">
            <div class="metric-icon">✅</div>
            <div class="metric-label">Completed Projects</div>
            <div class="metric-value">{{ $completedProjects }}</div>
        </div>
    </div>

    <!-- Bio Section -->
    @if($user->bio)
        <div class="section">
            <div class="section-title">
                <span class="section-title-icon blue">📝</span>
                Bio
            </div>
            <p class="bio-text">{{ $user->bio }}</p>
        </div>
    @endif

    <!-- Team Hierarchy -->
    <div class="section">
        <div class="section-title">
            <span class="section-title-icon purple">👥</span>
            Team Hierarchy
        </div>
        
        @if($team['manager'])
            <div class="hierarchy-section">
                <div class="hierarchy-label">Manager</div>
                <div class="hierarchy-item">
                    <div class="hierarchy-item-name">{{ $team['manager']->name }}</div>
                    <div class="hierarchy-item-role">{{ $team['manager']->position ?? 'N/A' }}</div>
                </div>
            </div>
        @endif

        @if($team['peers']->count() > 0)
            <div class="hierarchy-section">
                <div class="hierarchy-label">Peers</div>
                @foreach($team['peers'] as $peer)
                    <div class="hierarchy-item">
                        <div class="hierarchy-item-name">{{ $peer->name }}</div>
                        <div class="hierarchy-item-role">{{ $peer->position ?? 'N/A' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($team['subordinates']->count() > 0)
            <div class="hierarchy-section">
                <div class="hierarchy-label">Team Members</div>
                @foreach($team['subordinates'] as $subordinate)
                    <div class="hierarchy-item">
                        <div class="hierarchy-item-name">{{ $subordinate->name }}</div>
                        <div class="hierarchy-item-role">{{ $subordinate->position ?? 'N/A' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!$team['manager'] && $team['peers']->count() === 0 && $team['subordinates']->count() === 0)
            <p class="empty-text">No team hierarchy information available</p>
        @endif
    </div>

    <!-- Assigned Projects -->
    @if($totalProjects > 0)
        <div class="section">
            <div class="section-title">
                <span class="section-title-icon green">📁</span>
                Assigned Projects ({{ $totalProjects }})
            </div>
            <div class="projects-list">
                @foreach($user->projects as $project)
                    <div class="project-item">
                        <div class="project-name">{{ $project->name }}</div>
                        <span class="project-status {{ $project->status }}">
                            @if($project->status === 'completed')
                                ✅ Completed
                            @elseif($project->status === 'in-progress')
                                ⚙️ In Progress
                            @elseif($project->status === 'planning')
                                📋 Planning
                            @else
                                ⏸️ On Hold
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Activities -->
    <div class="section">
        <div class="section-title">
            <span class="section-title-icon orange">📊</span>
            Recent Activities
        </div>
        @if($recentActivities->count() > 0)
            @foreach($recentActivities as $activity)
                <div class="activity-item">
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
                    <div class="activity-content">
                        <div class="activity-description">{{ $activity->description }}</div>
                        <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
            <a href="{{ route('admin.users.activity-log', $user) }}" class="view-all-btn">
                📋 View Full Activity Log
            </a>
        @else
            <p class="empty-text">No activities yet</p>
        @endif
    </div>
</div>
@endsection
