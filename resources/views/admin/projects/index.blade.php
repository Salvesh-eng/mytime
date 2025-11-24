@extends('layouts.app')

@section('page-title', 'Projects')

@section('content')
<style>
    .progress-bar-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .progress-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
        border-left: 4px solid #2563EB;
    }

    .progress-card:hover {
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);
        transform: translateY(-2px);
    }

    .progress-card h3 {
        margin: 0 0 10px 0;
        font-size: 16px;
        color: #0F172A;
        font-weight: 600;
    }

    .tag {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        margin-right: 6px;
        margin-bottom: 8px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 12px;
        color: #6B7280;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .progress-fill {
        height: 100%;
        transition: width 0.3s ease;
        border-radius: 4px;
    }

    .progress-fill.green {
        background: linear-gradient(90deg, #16A34A, #15803d);
    }

    .progress-fill.blue {
        background: linear-gradient(90deg, #06B6D4, #0891b2);
    }

    .progress-fill.orange {
        background: linear-gradient(90deg, #F59E0B, #d97706);
    }

    .progress-fill.red {
        background: linear-gradient(90deg, #DC2626, #b91c1c);
    }

    .card-footer {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6B7280;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .filter-section .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .filter-section .form-group {
        margin-bottom: 0;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .action-buttons a, .action-buttons button {
        flex: 1;
        padding: 10px 12px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-box {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        border-left: 4px solid #2563EB;
    }

    .stat-box h4 {
        margin: 0;
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-box .value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
    }
    
    /* Pagination styling */
    .pagination-nav {
        padding: 10px 0;
    }
    
    .pagination-wrapper {
        max-width: 100%;
        overflow-x: auto;
        padding: 5px 0;
    }
    
    /* Hide some pagination elements on smaller screens */
    @media (max-width: 768px) {
        .pagination-nav .hidden.sm\:flex-1 {
            display: none !important;
        }
    }
</style>

<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Projects Management</h1>
        <p style="color: #6B7280; font-size: 14px;">Track and manage all your projects with progress bars, budgets, and milestones</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <!-- View Toggle -->
        <div style="display: flex; gap: 4px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 4px;">
            <button id="view-card-btn" onclick="switchView('card')" style="padding: 8px 14px; background: white; color: #6B7280; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">📇 Cards</button>
            <button id="view-table-btn" onclick="switchView('table')" style="padding: 8px 14px; background: #2563EB; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">📋 Table</button>
        </div>
        
        <a href="{{ route('admin.projects.templates') }}" style="background: #6B7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s;">📋 Templates</a>
        <a href="{{ route('admin.projects.archived') }}" style="background: #F59E0B; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s;">📦 Archive</a>
        <a href="/admin/projects/create" style="background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">+ New Project</a>
    </div>
</div>

<!-- Statistics -->
<div class="stats-row">
    <div class="stat-box">
        <h4>Total Projects</h4>
        <div class="value">{{ $projects->total() ?? 'N/A' }}</div>
    </div>
    <div class="stat-box" style="border-left-color: #06B6D4;">
        <h4>In Progress</h4>
        <div class="value" style="color: #06B6D4;">{{ count(array_filter($projects->items(), fn($p) => $p->status === 'in-progress')) }}</div>
    </div>
    <div class="stat-box" style="border-left-color: #16A34A;">
        <h4>Completed</h4>
        <div class="value" style="color: #16A34A;">{{ count(array_filter($projects->items(), fn($p) => $p->status === 'completed')) }}</div>
    </div>
    <div class="stat-box" style="border-left-color: #F59E0B;">
        <h4>Planning</h4>
        <div class="value" style="color: #F59E0B;">{{ count(array_filter($projects->items(), fn($p) => $p->status === 'planning')) }}</div>
    </div>
</div>
@if($projects->count() > 0)
    <!-- Card View -->
    <div id="card-view" class="progress-bar-container" style="display: none;">
        @forelse($projects as $project)
            <div class="progress-card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <h3>{{ $project->name }}</h3>
                        <div style="font-size: 12px; color: #6B7280;">
                            <span class="status-badge">
                                {{ ucfirst(str_replace('-', ' ', $project->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                @if($project->tags->count() > 0)
                    <div style="margin-bottom: 10px;">
                        @foreach($project->tags as $tag)
                            <span class="tag">{{ $tag->category }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Client -->
                @if($project->client)
                    <div style="font-size: 11px; color: #6B7280; margin-bottom: 10px;">👤 {{ $project->client->client_name }}</div>
                @endif

                <!-- Progress Bar (Hours) -->
                <div style="margin-bottom: 12px;">
                    <div class="progress-label">
                        <span>Progress (Hours)</span>
                        <span>{{ round($project->actual_hours, 1) }} / {{ round($project->estimated_hours, 1) }}</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="{{ min(100, $project->progress_percentage) }}"></div>
                    </div>
                </div>

                <!-- Budget Bar -->
                @if($project->budget)
                    <div style="margin-bottom: 12px;">
                        <div class="progress-label">
                            <span>Budget Used</span>
                            <span>${{ number_format($project->budget->spent_amount, 0) }} / ${{ number_format($project->budget->allocated_budget, 0) }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" data-width="{{ min(100, $project->budget_percentage) }}"></div>
                        </div>
                    </div>
                @endif

                <!-- Footer -->
                <div class="card-footer">
                    <span>👥 {{ $project->teamMembers->count() }} members</span>
                    <span>📅 {{ $project->due_date?->format('M d') ?? 'TBD' }}</span>
                </div>

                <!-- Action Buttons -->
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb; display: flex; gap: 8px;">
                    <a href="{{ route('admin.projects.show', $project) }}" style="flex: 1; background: #2563EB; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; text-align: center; transition: all 0.3s;">👁️ View</a>
                    <a href="{{ route('admin.projects.edit', $project) }}" style="flex: 1; background: #06B6D4; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; text-align: center; transition: all 0.3s;">✏️ Edit</a>
                    <button class="delete-project-btn" data-id="{{ $project->id }}" style="flex: 1; background: #DC2626; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s;">🗑️ Delete</button>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 8px;">
                <p style="color: #6B7280; margin: 0;">No projects found.</p>
            </div>
        @endforelse
    </div>

    <!-- Table View -->
    <div id="table-view" style="display: block; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <tr>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #0F172A; font-size: 13px;">Project Name</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #0F172A; font-size: 13px;">Status</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #0F172A; font-size: 13px;">Client</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #0F172A; font-size: 13px;">Progress</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #0F172A; font-size: 13px;">Team</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #0F172A; font-size: 13px;">Due Date</th>
                    <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #0F172A; font-size: 13px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                        <td style="padding: 14px 16px; color: #0F172A; font-size: 13px; font-weight: 500;">{{ $project->name }}</td>
                        <td style="padding: 14px 16px; font-size: 13px;">
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; 
                                @if($project->status === 'completed') background: #dcfce7; color: #15803d;
                                @elseif($project->status === 'in-progress') background: #dbeafe; color: #0c4a6e;
                                @elseif($project->status === 'planning') background: #fef3c7; color: #92400e;
                                @else background: #f3f4f6; color: #475569;
                                @endif
                            ">{{ ucfirst(str_replace('-', ' ', $project->status)) }}</span>
                        </td>
                        <td style="padding: 14px 16px; color: #6B7280; font-size: 13px;">{{ $project->client?->client_name ?? 'N/A' }}</td>
                        <td style="padding: 14px 16px; font-size: 13px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                                    <?php $progressWidth = min(100, $project->progress_percentage); ?>
                                    <div style="width: <?php echo $progressWidth; ?>%; height: 100%; background: linear-gradient(90deg, #2563EB, #1d4ed8); border-radius: 3px;"></div>
                                </div>
                                <span style="color: #6B7280; font-size: 12px;">{{ round($project->progress_percentage, 0) }}%</span>
                            </div>
                        </td>
                        <td style="padding: 14px 16px; color: #6B7280; font-size: 13px;">{{ $project->teamMembers->count() }} members</td>
                        <td style="padding: 14px 16px; color: #6B7280; font-size: 13px;">{{ $project->due_date?->format('M d, Y') ?? 'TBD' }}</td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="{{ route('admin.projects.show', $project) }}" title="View" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #2563EB; color: white; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.3s;">👁️</a>
                                <a href="{{ route('admin.projects.edit', $project) }}" title="Edit" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #06B6D4; color: white; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.3s;">✏️</a>
                                <button class="delete-project-btn" data-id="{{ $project->id }}" title="Delete" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #DC2626; color: white; border-radius: 6px; border: none; font-size: 14px; cursor: pointer; transition: all 0.3s;">🗑️</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $projects->links('vendor.pagination.compact') }}
            </div>
        </div>
    @endif
@else
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 60px 20px; text-align: center;">
        <div style="font-size: 48px; margin-bottom: 16px;">📁</div>
        <p style="color: #6B7280; font-size: 16px; margin-bottom: 20px;">No projects yet</p>
        <a href="/admin/projects/create" style="display: inline-block; background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s;">Create your first project</a>
    </div>
@endif

@endsection

@section('scripts')
<script>
    // Apply progress bar widths from data attributes
    document.querySelectorAll('.progress-fill[data-width]').forEach(el => {
        el.style.width = el.getAttribute('data-width') + '%';
    });

    // View switching functionality
    function switchView(view) {
        const cardView = document.getElementById('card-view');
        const tableView = document.getElementById('table-view');
        const cardBtn = document.getElementById('view-card-btn');
        const tableBtn = document.getElementById('view-table-btn');

        if (view === 'card') {
            cardView.style.display = 'grid';
            tableView.style.display = 'none';
            cardBtn.style.background = '#2563EB';
            cardBtn.style.color = 'white';
            tableBtn.style.background = 'white';
            tableBtn.style.color = '#6B7280';
            localStorage.setItem('projectsView', 'card');
        } else {
            cardView.style.display = 'none';
            tableView.style.display = 'block';
            cardBtn.style.background = 'white';
            cardBtn.style.color = '#6B7280';
            tableBtn.style.background = '#2563EB';
            tableBtn.style.color = 'white';
            localStorage.setItem('projectsView', 'table');
        }
    }

    // Delete project function
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-project-btn')) {
            const projectId = e.target.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/projects/' + projectId;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_token';
                    input.value = csrfToken.content;
                    form.appendChild(input);
                }
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    });

    // Load saved view preference on page load - default to table view
    window.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('projectsView') || 'table';
        switchView(savedView);
    });
</script>
@endsection