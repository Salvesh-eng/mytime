@extends('layouts.app')

@section('page-title', 'Notifications')

@section('content')
<div style="margin-bottom: 30px;">
    <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Notifications</h1>
    <p style="color: #6B7280; font-size: 14px;">Stay updated with your projects and deadlines</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
    <!-- Upcoming Projects -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); padding: 20px; color: white;">
            <h2 style="font-size: 18px; margin: 0; display: flex; align-items: center; gap: 8px;">
                📅 Upcoming Projects
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; opacity: 0.9;">Starting within 30 days</p>
        </div>
        <div style="padding: 20px;">
            @if($upcomingProjects->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($upcomingProjects as $project)
                        @php
                            $daysUntil = \Carbon\Carbon::now()->diffInDays($project->start_date);
                        @endphp
                        <div style="padding: 16px; background-color: #f0f9ff; border-radius: 8px; border-left: 4px solid #2563EB; display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <h3 style="font-size: 14px; color: #0F172A; font-weight: 600; margin: 0;">{{ $project->name }}</h3>
                                    <span style="background-color: #2563EB; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ $daysUntil }} day{{ $daysUntil !== 1 ? 's' : '' }}</span>
                                </div>
                                <p style="margin: 0; color: #6B7280; font-size: 12px;">Starts: {{ $project->start_date->format('M d, Y') }}</p>
                                <p style="margin: 4px 0 0 0; color: #9CA3AF; font-size: 12px;">Status: <span style="font-weight: 600; color: #0F172A;">{{ ucfirst(str_replace('-', ' ', $project->status)) }}</span></p>
                            </div>
                            <div style="display: flex; gap: 8px; margin-left: 12px;">
                                <a href="/admin/projects/{{ $project->id }}/edit" style="display: inline-block; padding: 6px 12px; background-color: #2563EB; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; transition: all 0.3s;">👁️ View</a>
                                <button onclick="markAsRead(this, '{{ $project->notification_id }}')" style="display: inline-block; padding: 6px 12px; background-color: #06B6D4; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;">✓ Read</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 20px; color: #9CA3AF;">
                    <p style="margin: 0;">No upcoming projects</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Due Soon -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #F59E0B 0%, #d97706 100%); padding: 20px; color: white;">
            <h2 style="font-size: 18px; margin: 0; display: flex; align-items: center; gap: 8px;">
                ⏰ Due Soon
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; opacity: 0.9;">Due within 30 days</p>
        </div>
        <div style="padding: 20px;">
            @if($dueProjects->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($dueProjects as $project)
                        @php
                            $daysUntil = $project->due_date ? \Carbon\Carbon::now()->diffInDays($project->due_date) : 0;
                        @endphp
                        <div style="padding: 16px; background-color: #fffbeb; border-radius: 8px; border-left: 4px solid #F59E0B; display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <h3 style="font-size: 14px; color: #0F172A; font-weight: 600; margin: 0;">{{ $project->name }}</h3>
                                    <span style="background-color: #F59E0B; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">{{ $daysUntil }} day{{ $daysUntil !== 1 ? 's' : '' }}</span>
                                </div>
                                <p style="margin: 0; color: #6B7280; font-size: 12px;">Due: {{ $project->due_date ? $project->due_date->format('M d, Y') : 'N/A' }}</p>
                                <p style="margin: 4px 0 0 0; color: #9CA3AF; font-size: 12px;">Progress: <span style="font-weight: 600; color: #0F172A;">{{ $project->progress }}%</span></p>
                            </div>
                            <div style="display: flex; gap: 8px; margin-left: 12px;">
                                <a href="/admin/projects/{{ $project->id }}/edit" style="display: inline-block; padding: 6px 12px; background-color: #F59E0B; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; transition: all 0.3s;">👁️ View</a>
                                <button onclick="markAsRead(this, '{{ $project->id }}')" style="display: inline-block; padding: 6px 12px; background-color: #06B6D4; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;">✓ Read</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 20px; color: #9CA3AF;">
                    <p style="margin: 0;">No projects due soon</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recently Added -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #06B6D4 0%, #0891b2 100%); padding: 20px; color: white;">
            <h2 style="font-size: 18px; margin: 0; display: flex; align-items: center; gap: 8px;">
                📝 Recently Added
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; opacity: 0.9;">Added within 7 days</p>
        </div>
        <div style="padding: 20px;">
            @if($recentProjects->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($recentProjects as $project)
                        <div style="padding: 16px; background-color: #ecfdf5; border-radius: 8px; border-left: 4px solid #06B6D4; display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <h3 style="font-size: 14px; color: #0F172A; font-weight: 600; margin: 0;">{{ $project->name }}</h3>
                                    <span style="background-color: #06B6D4; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">New</span>
                                </div>
                                <p style="margin: 0; color: #6B7280; font-size: 12px;">Added: {{ $project->created_at->format('M d, Y g:i A') }}</p>
                                <p style="margin: 4px 0 0 0; color: #9CA3AF; font-size: 12px;">Status: <span style="font-weight: 600; color: #0F172A;">{{ ucfirst(str_replace('-', ' ', $project->status)) }}</span></p>
                            </div>
                            <div style="display: flex; gap: 8px; margin-left: 12px;">
                                <a href="/admin/projects/{{ $project->id }}/edit" style="display: inline-block; padding: 6px 12px; background-color: #06B6D4; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; transition: all 0.3s;">👁️ View</a>
                                <button onclick="markAsRead(this, '{{ $project->notification_id }}')" style="display: inline-block; padding: 6px 12px; background-color: #16A34A; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;">✓ Read</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 20px; color: #9CA3AF;">
                    <p style="margin: 0;">No recent projects</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div style="margin-top: 40px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; text-align: center;">
        <h3 style="color: #6B7280; font-size: 13px; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">📅 Upcoming</h3>
        <div style="font-size: 36px; font-weight: 700; color: #2563EB;">{{ $upcomingProjects->count() }}</div>
    </div>
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; text-align: center;">
        <h3 style="color: #6B7280; font-size: 13px; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">⏰ Due Soon</h3>
        <div style="font-size: 36px; font-weight: 700; color: #F59E0B;">{{ $dueProjects->count() }}</div>
    </div>
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; text-align: center;">
        <h3 style="color: #6B7280; font-size: 13px; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">📝 Recent</h3>
        <div style="font-size: 36px; font-weight: 700; color: #06B6D4;">{{ $recentProjects->count() }}</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function markAsRead(button, notificationId) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/admin/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate the button
                button.style.opacity = '0.5';
                button.textContent = '✓ Done';
                button.style.backgroundColor = '#9CA3AF';
                
                // Optionally reload after a short delay to refresh the page
                setTimeout(() => {
                    location.reload();
                }, 800);
            } else {
                alert('Failed to mark as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking notification as read');
        });
    }
</script>
@endsection
