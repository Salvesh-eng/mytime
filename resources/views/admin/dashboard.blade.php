@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

@section('content')
    <!-- Header Section -->
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Welcome to Dashboard</h1>
            <p style="color: #6B7280; font-size: 14px;">Here's what's happening with your projects and team</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/add-time" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, #16A34A 0%, #15803d 100%); color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);">▶ Quick Timer</a>
            <div style="background: white; padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e7eb; text-align: right;">
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Last updated</div>
                <div style="font-size: 14px; font-weight: 600; color: #2563EB;" id="last-updated-time">Just now</div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 24px;">
        <!-- Total Users Card -->
        <div style="background: linear-gradient(135deg, #dbeafe 0%, #ecf8ff 100%); border-radius: 10px; border: 2px solid #0ea5e9; padding: 14px; box-shadow: 0 2px 4px rgba(6, 182, 212, 0.1);">
            <h3 style="color: #0c4a6e; font-size: 10px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px;">👥 Total Users</h3>
            <div style="font-size: 24px; font-weight: 700; color: #0ea5e9;" id="metric-users">{{ $totalUsers }}</div>
            <div style="font-size: 10px; color: #0ea5e9; margin-top: 6px; font-weight: 600;">Active team members</div>
        </div>

        <!-- Hours Logged Today Card -->
        <div style="background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); border-radius: 10px; border: 2px solid #16a34a; padding: 14px; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.1);">
            <h3 style="color: #15803d; font-size: 10px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px;">⏱️ Hours Today</h3>
            <div style="font-size: 24px; font-weight: 700; color: #16a34a;" id="metric-hours">{{ number_format($hoursLoggedToday, 2) }}</div>
            <div style="font-size: 10px; color: #16a34a; margin-top: 6px; font-weight: 600;">Total hours logged</div>
        </div>

        <!-- Pending Approvals Card -->
        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fef9e7 100%); border-radius: 10px; border: 2px solid #f59e0b; padding: 14px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.1);">
            <h3 style="color: #92400e; font-size: 10px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px;">⏳ Pending Approvals</h3>
            <div style="font-size: 24px; font-weight: 700; color: #f59e0b;" id="metric-pending">{{ $pendingApprovals }}</div>
            <div style="font-size: 10px; color: #f59e0b; margin-top: 6px; font-weight: 600;">Awaiting review</div>
        </div>

        <!-- Total Projects Card -->
        <div style="background: linear-gradient(135deg, #f3e8ff 0%, #faf5ff 100%); border-radius: 10px; border: 2px solid #a855f7; padding: 14px; box-shadow: 0 2px 4px rgba(168, 85, 247, 0.1);">
            <h3 style="color: #6b21a8; font-size: 10px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px;">📁 Total Projects</h3>
            <div style="font-size: 24px; font-weight: 700; color: #a855f7;" id="metric-projects">{{ $totalProjects }}</div>
            <div style="font-size: 10px; color: #a855f7; margin-top: 6px; font-weight: 600;">Active projects</div>
        </div>
    </div>

    <!-- Widget Container -->
    <div id="dashboard-widgets" style="display: flex; flex-direction: column; gap: 32px;">

    <!-- Widget: Quick Actions -->
    <div class="dashboard-widget" data-widget-id="actions" style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 8px 16px 8px; border-bottom: 1px solid #e5e7eb; margin-bottom: 16px;">
            <h2 style="font-size: 16px; color: #0F172A; font-weight: 600; margin: 0; cursor: move; flex: 1;">⚡ Quick Actions</h2>
        </div>
        <div class="widget-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #06B6D4; transition: all 0.3s; hover: box-shadow 0 8px 12px rgba(0, 0, 0, 0.1);">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📁 Create New Project</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Start a new project and assign team members to begin tracking work.</p>
                <a href="/admin/projects/create" style="display: inline-block; padding: 8px 16px; background-color: #06B6D4; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Create Project</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #16A34A; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">👥 Create New User</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Add a new team member to the system and manage their access and permissions.</p>
                <a href="/admin/users/create" style="display: inline-block; padding: 8px 16px; background-color: #16A34A; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Create User</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #2563EB; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">👤 Manage Users</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">View, edit, and manage all users in your organization with full control.</p>
                <a href="/admin/users" style="display: inline-block; padding: 8px 16px; background-color: #2563EB; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Manage Users</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #F59E0B; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">⏱️ Review Time Entries</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Review and approve pending time entries submitted by team members.</p>
                <a href="/admin/time-entries" style="display: inline-block; padding: 8px 16px; background-color: #F59E0B; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Review Entries</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #DC2626; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📊 View Reports</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Generate and view comprehensive reports on time tracking and productivity.</p>
                <a href="/admin/reports" style="display: inline-block; padding: 8px 16px; background-color: #DC2626; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">View Reports</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #8B5CF6; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📋 View All Projects</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Browse all projects, manage their status, progress, and assigned team members.</p>
                <a href="/admin/projects" style="display: inline-block; padding: 8px 16px; background-color: #8B5CF6; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">View Projects</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #EC4899; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📈 View Analytics</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Access detailed analytics and insights about team productivity and time tracking trends.</p>
                <a href="/admin/analytics" style="display: inline-block; padding: 8px 16px; background-color: #EC4899; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">View Analytics</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #14B8A6; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">👥 Manage Team Members</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Organize and manage team member assignments, roles, and project participation.</p>
                <a href="/admin/team-members" style="display: inline-block; padding: 8px 16px; background-color: #14B8A6; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Manage Team</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #6366F1; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">🔔 View Notifications</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Check all system notifications, alerts, and important updates for your organization.</p>
                <a href="/admin/notifications" style="display: inline-block; padding: 8px 16px; background-color: #6366F1; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">View Notifications</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #F97316; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">📦 Project Templates</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Create and manage project templates to streamline project creation and setup.</p>
                <a href="/admin/projects/templates" style="display: inline-block; padding: 8px 16px; background-color: #F97316; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Manage Templates</a>
            </div>

            <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; border-left: 4px solid #06B6D4; transition: all 0.3s;">
                <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 10px;">⚙️ System Settings</h3>
                <p style="color: #6B7280; font-size: 13px; margin-bottom: 16px; line-height: 1.5;">Configure system settings, preferences, and organizational policies.</p>
                <a href="/admin/settings" style="display: inline-block; padding: 8px 16px; background-color: #06B6D4; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.3s;">Settings</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Widget: Real-Time Projects Summary -->
    <div class="dashboard-widget" data-widget-id="projects" style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 8px 16px 8px; border-bottom: 1px solid #e5e7eb; margin-bottom: 16px;">
            <h2 style="font-size: 16px; color: #0F172A; font-weight: 600; margin: 0; cursor: move; flex: 1;">📁 Projects Overview</h2>
            <a href="/admin/projects" style="display: inline-block; padding: 8px 16px; background-color: #2563EB; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; transition: all 0.3s;">View All Projects</a>
        </div>
        <div class="widget-content" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            @forelse($allProjects as $project)
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
                @endphp
                <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); transition: all 0.3s; border-left: 4px solid {{ $color }};">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                        <h3 style="font-size: 14px; color: #0F172A; font-weight: 600; margin: 0; flex: 1;">{{ $icon }} {{ $project->name }}</h3>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <span style="font-size: 12px; color: #6B7280; font-weight: 500;">Progress</span>
                            <span style="font-size: 12px; color: #0F172A; font-weight: 600;">{{ $project->progress }}%</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ $project->progress }}%; height: 100%; background: linear-gradient(90deg, {{ $color }}, {{ $color }}cc); transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="display: inline-block; padding: 4px 10px; background: {{ $color }}20; color: {{ $color }}; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: capitalize;">{{ str_replace('-', ' ', $project->status) }}</span>
                        <span style="font-size: 11px; color: #9ca3af;">{{ $project->updated_at->diffForHumans() }}</span>
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
    
    // Initial setup
    window.addEventListener('DOMContentLoaded', function() {
        initializeWidgetCustomization();
        updateActivityFeed();
    });
</script>
@endsection
