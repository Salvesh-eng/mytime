@extends('layouts.app')

@section('page-title', 'Projects')

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

    .progress-bar-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .progress-card {
        background: linear-gradient(135deg, #FFE5F0 0%, #E5F0FF 100%);
        padding: 12px;
        border-radius: 6px;
        box-shadow: 0 1px 4px rgba(255, 179, 217, 0.15);
        transition: all 0.3s;
        border-left: 3px solid var(--soft-pink);
    }

    .progress-card:hover {
        box-shadow: 0 2px 8px rgba(255, 179, 217, 0.25);
        transform: translateY(-1px);
    }

    .progress-card h3 {
        margin: 0 0 6px 0;
        font-size: 14px;
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
        background: linear-gradient(90deg, var(--soft-green), var(--light-green));
    }

    .progress-fill.blue {
        background: linear-gradient(90deg, var(--soft-blue), var(--soft-lavender));
    }

    .progress-fill.orange {
        background: linear-gradient(90deg, var(--soft-orange), var(--soft-peach));
    }

    .progress-fill.red {
        background: linear-gradient(90deg, var(--soft-pink), var(--light-pink));
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
        background: linear-gradient(135deg, #FFE5F0 0%, #E5F0FF 100%);
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(255, 179, 217, 0.15);
        text-align: center;
        border-left: 4px solid var(--soft-pink);
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
        padding: 20px 0;
        margin-top: 30px;
    }

    .pagination-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .pagination-mobile {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .pagination-desktop {
        display: none;
        flex-direction: column;
        gap: 15px;
    }

    .pagination-info {
        text-align: center;
    }

    .pagination-text {
        font-size: 14px;
        color: #6B7280;
    }

    .pagination-text .font-semibold {
        font-weight: 600;
        color: #0F172A;
    }

    .pagination-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .pagination-links {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
        align-items: center;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid var(--soft-pink);
        background: white;
        color: #0F172A;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        min-width: 40px;
        height: 40px;
    }

    .pagination-btn-active {
        color: #0F172A;
        border-color: var(--soft-blue);
        background: var(--soft-blue);
    }

    .pagination-btn-active:hover {
        background: var(--soft-lavender);
        box-shadow: 0 2px 4px rgba(179, 217, 255, 0.3);
    }

    .pagination-btn-link {
        color: #6B7280;
        border-color: var(--soft-pink);
    }

    .pagination-btn-link:hover {
        background: var(--soft-peach);
        color: #0F172A;
        border-color: var(--soft-orange);
    }

    .pagination-btn-current {
        background: var(--soft-green);
        color: #0F172A;
        border-color: var(--soft-green);
        font-weight: 700;
    }

    .pagination-btn-disabled {
        background: #f3f4f6;
        color: #9CA3AF;
        border-color: var(--soft-pink);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 6px;
        color: #6B7280;
        font-size: 13px;
    }

    .pagination-icon {
        width: 18px;
        height: 18px;
    }

    .pagination-wrapper {
        max-width: 100%;
        overflow-x: auto;
        padding: 5px 0;
    }

    /* Status dropdown styling */
    .status-dropdown {
        padding: 4px 8px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        background: white;
        color: #0F172A;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }

    .status-dropdown:hover {
        border-color: var(--soft-pink);
        box-shadow: 0 1px 3px rgba(255, 179, 217, 0.15);
    }

    .status-dropdown:focus {
        outline: none;
        border-color: var(--soft-blue);
        box-shadow: 0 0 0 2px rgba(179, 217, 255, 0.15);
    }

    /* Interactive progress bar */
    .progress-bar-interactive {
        cursor: pointer;
        position: relative;
    }

    .progress-bar-interactive:hover {
        opacity: 0.8;
    }

    .progress-bar-interactive .progress-fill {
        transition: width 0.2s ease;
    }

    .progress-input-container {
        display: none;
        margin-top: 8px;
        gap: 8px;
        align-items: center;
    }

    .progress-input-container.active {
        display: flex;
    }

    .progress-input-container input {
        flex: 1;
        padding: 6px 10px;
        border: 1px solid var(--soft-pink);
        border-radius: 4px;
        font-size: 12px;
    }

    .progress-input-container button {
        padding: 6px 12px;
        background: var(--soft-green);
        color: #0F172A;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .progress-input-container button:hover {
        background: #B3FFB3;
    }

    .progress-input-container button.cancel {
        background: var(--soft-pink);
    }

    .progress-input-container button.cancel:hover {
        background: #FFB3D9;
    }

    /* Responsive design */
    @media (min-width: 768px) {
        .pagination-mobile {
            display: none;
        }

        .pagination-desktop {
            display: flex;
        }

        .pagination-info {
            text-align: left;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .pagination-links {
            justify-content: flex-end;
        }
    }

    @media (max-width: 640px) {
        .pagination-btn {
            padding: 6px 10px;
            font-size: 12px;
            min-width: 36px;
            height: 36px;
        }

        .pagination-buttons {
            gap: 8px;
        }

        .pagination-links {
            gap: 4px;
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
        <div style="display: flex; gap: 4px; background: linear-gradient(135deg, #FFE5F0 0%, #E5F0FF 100%); border: 1px solid var(--soft-pink); border-radius: 8px; padding: 4px;">
            <button id="view-card-btn" onclick="switchView('card')" style="padding: 8px 14px; background: white; color: #6B7280; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">📇 Cards</button>
            <button id="view-table-btn" onclick="switchView('table')" style="padding: 8px 14px; background: var(--soft-blue); color: #0F172A; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">📋 Table</button>
        </div>
        
        <a href="{{ route('admin.projects.templates') }}" style="background: var(--soft-purple); color: #0F172A; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(217, 179, 255, 0.3);">📋 Templates</a>
        <a href="{{ route('admin.projects.archived') }}" style="background: var(--soft-orange); color: #0F172A; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(255, 217, 179, 0.3);">📦 Archive</a>
        <a href="/admin/projects/create" style="background: linear-gradient(135deg, var(--soft-mint) 0%, var(--soft-green) 100%); color: #0F172A; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(179, 255, 217, 0.3);">+ New Project</a>
    </div>
</div>

<!-- Statistics -->
<div class="stats-row">
    <div class="stat-box" style="background: linear-gradient(135deg, #FFE5F0 0%, #FFCCF0 100%); border-left-color: var(--soft-pink);">
        <h4>Total Projects</h4>
        <div class="value" style="color: #FF69B4;">{{ $projects->total() ?? 'N/A' }}</div>
    </div>
    <div class="stat-box" style="background: linear-gradient(135deg, #E5F0FF 0%, #E5F5FF 100%); border-left-color: var(--soft-blue);">
        <h4>In Progress</h4>
        <div class="value" style="color: #4A90E2;">{{ count(array_filter($projects->items(), fn($p) => $p->status === 'in-progress')) }}</div>
    </div>
    <div class="stat-box" style="background: linear-gradient(135deg, #E5FFE5 0%, #F0FFF0 100%); border-left-color: var(--soft-green);">
        <h4>Completed</h4>
        <div class="value" style="color: #52C41A;">{{ count(array_filter($projects->items(), fn($p) => $p->status === 'completed')) }}</div>
    </div>
    <div class="stat-box" style="background: linear-gradient(135deg, #FFF0E5 0%, #FFFAF0 100%); border-left-color: var(--soft-orange);">
        <h4>Overdue</h4>
        <div class="value" style="color: #FF9C6E;">{{ $overdueCount ?? 0 }}</div>
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
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">
                            <select class="status-dropdown" data-project-id="{{ $project->id }}" onchange="updateProjectStatus(this)">
                                <option value="planning" {{ $project->status === 'planning' ? 'selected' : '' }}>📋 Planning</option>
                                <option value="not-started" {{ $project->status === 'not-started' ? 'selected' : '' }}>🚀 Not Started</option>
                                <option value="in-progress" {{ $project->status === 'in-progress' ? 'selected' : '' }}>⚙️ In Progress</option>
                                <option value="awaiting-input" {{ $project->status === 'awaiting-input' ? 'selected' : '' }}>⏳ Awaiting Input</option>
                                <option value="testing" {{ $project->status === 'testing' ? 'selected' : '' }}>🧪 Testing</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                <option value="on-hold" {{ $project->status === 'on-hold' ? 'selected' : '' }}>⏸️ On Hold</option>
                                <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                <option value="overdue" {{ $project->status === 'overdue' ? 'selected' : '' }}>⚠️ Overdue</option>
                            </select>
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

                <!-- Progress Bar (Hours) - Interactive -->
                <div style="margin-bottom: 12px;">
                    <div class="progress-label">
                        <span>Progress (Hours)</span>
                        <span>{{ round($project->actual_hours, 1) }} / {{ round($project->estimated_hours, 1) }}</span>
                    </div>
                    <div class="progress-bar progress-bar-interactive" onclick="toggleProgressInput(this, {{ $project->id }})">
                        <div class="progress-fill" data-width="{{ min(100, $project->progress_percentage) }}" style="width: {{ min(100, $project->progress_percentage) }}%;"></div>
                    </div>
                    <div class="progress-input-container">
                        <input type="range" min="0" max="100" value="{{ min(100, $project->progress_percentage) }}" class="progress-slider" onchange="updateProgressValue(this)">
                        <span class="progress-value" style="min-width: 40px; text-align: right; font-size: 12px; color: #6B7280;">{{ round($project->progress_percentage, 0) }}%</span>
                        <button onclick="saveProgress(this, {{ $project->id }})">Save</button>
                        <button class="cancel" onclick="cancelProgressEdit(this)">Cancel</button>
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
                            <div class="progress-fill" data-width="{{ min(100, $project->budget_percentage) }}" style="width: {{ min(100, $project->budget_percentage) }}%;"></div>
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
                    <a href="{{ route('admin.projects.show', $project) }}" style="flex: 1; background: var(--soft-blue); color: #0F172A; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; text-align: center; transition: all 0.3s; box-shadow: 0 2px 4px rgba(179, 217, 255, 0.2);">👁️ View</a>
                    <a href="{{ route('admin.projects.edit', $project) }}" style="flex: 1; background: var(--soft-mint); color: #0F172A; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; text-align: center; transition: all 0.3s; box-shadow: 0 2px 4px rgba(179, 255, 217, 0.2);">✏️ Edit</a>
                    <button class="delete-project-btn" data-id="{{ $project->id }}" style="flex: 1; background: var(--soft-pink); color: #0F172A; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(255, 179, 217, 0.2);">🗑️ Delete</button>
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
                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #0F172A; font-size: 12px;">Project Name</th>
                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #0F172A; font-size: 12px;">Status</th>
                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #0F172A; font-size: 12px;">Client</th>
                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #0F172A; font-size: 12px;">Progress</th>
                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #0F172A; font-size: 12px;">Team</th>
                    <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #0F172A; font-size: 12px;">Due Date</th>
                    <th style="padding: 10px 12px; text-align: center; font-weight: 600; color: #0F172A; font-size: 12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                        <td style="padding: 10px 12px; color: #0F172A; font-size: 12px; font-weight: 500;">{{ $project->name }}</td>
                        <td style="padding: 10px 12px; font-size: 12px;">
                            <select class="status-dropdown" data-project-id="{{ $project->id }}" onchange="updateProjectStatus(this)" style="width: 100%;">
                                <option value="planning" {{ $project->status === 'planning' ? 'selected' : '' }}>📋 Planning</option>
                                <option value="not-started" {{ $project->status === 'not-started' ? 'selected' : '' }}>🚀 Not Started</option>
                                <option value="in-progress" {{ $project->status === 'in-progress' ? 'selected' : '' }}>⚙️ In Progress</option>
                                <option value="awaiting-input" {{ $project->status === 'awaiting-input' ? 'selected' : '' }}>⏳ Awaiting Input</option>
                                <option value="testing" {{ $project->status === 'testing' ? 'selected' : '' }}>🧪 Testing</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                <option value="on-hold" {{ $project->status === 'on-hold' ? 'selected' : '' }}>⏸️ On Hold</option>
                                <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                <option value="overdue" {{ $project->status === 'overdue' ? 'selected' : '' }}>⚠️ Overdue</option>
                            </select>
                        </td>
                        <td style="padding: 10px 12px; color: #6B7280; font-size: 12px;">{{ $project->client?->client_name ?? 'N/A' }}</td>
                        <td style="padding: 10px 12px; font-size: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px; cursor: pointer;" onclick="toggleProgressInputTable(this, {{ $project->id }})">
                                <div style="width: 50px; height: 5px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                                    <?php $progressWidth = min(100, $project->progress_percentage); ?>
                                    <div style="width: <?php echo $progressWidth; ?>%; height: 100%; background: linear-gradient(90deg, #B3D9FF, #E6D9FF); border-radius: 3px;"></div>
                                </div>
                                <span style="color: #6B7280; font-size: 11px;">{{ round($project->progress_percentage, 0) }}%</span>
                            </div>
                            <div class="progress-input-container" style="margin-top: 6px;">
                                <input type="range" min="0" max="100" value="{{ min(100, $project->progress_percentage) }}" class="progress-slider" onchange="updateProgressValue(this)" style="flex: 1;">
                                <span class="progress-value" style="min-width: 35px; text-align: right; font-size: 11px; color: #6B7280;">{{ round($project->progress_percentage, 0) }}%</span>
                                <button onclick="saveProgress(this, {{ $project->id }})" style="padding: 3px 6px; font-size: 10px;">Save</button>
                                <button class="cancel" onclick="cancelProgressEdit(this)" style="padding: 3px 6px; font-size: 10px;">Cancel</button>
                            </div>
                        </td>
                        <td style="padding: 10px 12px; color: #6B7280; font-size: 12px;">
                            @if($project->teamMembers->count() > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    @foreach($project->teamMembers->take(2) as $member)
                                        <span style="display: inline-block; padding: 3px 8px; background: var(--soft-mint); color: #0F172A; border-radius: 10px; font-size: 11px; font-weight: 500;">{{ $member->name }}</span>
                                    @endforeach
                                    @if($project->teamMembers->count() > 2)
                                        <span style="display: inline-block; padding: 3px 8px; background: var(--soft-lavender); color: #0F172A; border-radius: 10px; font-size: 11px; font-weight: 500;">+{{ $project->teamMembers->count() - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span style="color: #9CA3AF; font-style: italic; font-size: 11px;">No members</span>
                            @endif
                        </td>
                        <td style="padding: 10px 12px; color: #6B7280; font-size: 12px;">{{ $project->due_date?->format('M d') ?? 'TBD' }}</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="{{ route('admin.projects.show', $project) }}" title="View" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--soft-blue); color: #0F172A; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 4px rgba(179, 217, 255, 0.2);">👁️</a>
                                <a href="{{ route('admin.projects.edit', $project) }}" title="Edit" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--soft-mint); color: #0F172A; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 4px rgba(179, 255, 217, 0.2);">✏️</a>
                                <button class="delete-project-btn" data-id="{{ $project->id }}" title="Delete" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--soft-pink); color: #0F172A; border-radius: 6px; border: none; font-size: 14px; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(255, 179, 217, 0.2);">🗑️</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="pagination-nav">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                <!-- Pagination Controls -->
                <div class="pagination-controls">
                    {{-- Previous Page Link --}}
                    @if ($projects->onFirstPage())
                        <span class="pagination-btn pagination-btn-disabled">&lt;</span>
                    @else
                        <a href="{{ $projects->previousPageUrl() }}" class="pagination-btn pagination-btn-link">&lt;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $current = $projects->currentPage();
                        $last = $projects->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $projects->url(1) }}" class="pagination-btn pagination-btn-link">1</a>
                        @if ($start > 2)
                            <span class="pagination-dots">...</span>
                        @endif
                    @endif

                    @foreach ($projects->getUrlRange($start, $end) as $page => $url)
                        @if ($page == $current)
                            <span class="pagination-btn pagination-btn-current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-btn pagination-btn-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="pagination-dots">...</span>
                        @endif
                        <a href="{{ $projects->url($last) }}" class="pagination-btn pagination-btn-link">{{ $last }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($projects->hasMorePages())
                        <a href="{{ $projects->nextPageUrl() }}" class="pagination-btn pagination-btn-link">&gt;</a>
                    @else
                        <span class="pagination-btn pagination-btn-disabled">&gt;</span>
                    @endif
                </div>

                <!-- Results Info -->
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="font-size: 14px; color: #6B7280; font-weight: 500;">
                        Results: <span style="color: #0F172A; font-weight: 600;">{{ $projects->firstItem() }} - {{ $projects->lastItem() }}</span> of <span style="color: #0F172A; font-weight: 600;">{{ $projects->total() }}</span>
                    </span>
                    <select style="padding: 6px 12px; border: 1px solid var(--soft-pink); border-radius: 6px; background: white; color: #0F172A; font-weight: 600; cursor: pointer; font-size: 13px;" onchange="window.location.href = this.value">
                        <option value="{{ $projects->url(1) }}?per_page=10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="{{ $projects->url(1) }}?per_page=25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="{{ $projects->url(1) }}?per_page=50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="{{ $projects->url(1) }}?per_page=100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>
    @endif
@else
    <div style="background: linear-gradient(135deg, #FFE5F0 0%, #E5F0FF 100%); border-radius: 16px; border: 1px solid var(--soft-pink); box-shadow: 0 4px 6px rgba(255, 179, 217, 0.1); padding: 60px 20px; text-align: center;">
        <div style="font-size: 48px; margin-bottom: 16px;">📁</div>
        <p style="color: #6B7280; font-size: 16px; margin-bottom: 20px;">No projects yet</p>
        <a href="/admin/projects/create" style="display: inline-block; background: linear-gradient(135deg, var(--soft-mint) 0%, var(--soft-green) 100%); color: #0F172A; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(179, 255, 217, 0.3);">Create your first project</a>
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
            cardBtn.style.background = '#B3FFB3';
            cardBtn.style.color = '#0F172A';
            tableBtn.style.background = 'white';
            tableBtn.style.color = '#6B7280';
            localStorage.setItem('projectsView', 'card');
        } else {
            cardView.style.display = 'none';
            tableView.style.display = 'block';
            cardBtn.style.background = 'white';
            cardBtn.style.color = '#6B7280';
            tableBtn.style.background = '#B3D9FF';
            tableBtn.style.color = '#0F172A';
            localStorage.setItem('projectsView', 'table');
        }
    }

    // Toggle progress input for card view
    function toggleProgressInput(element, projectId) {
        const container = element.nextElementSibling;
        container.classList.toggle('active');
        if (container.classList.contains('active')) {
            const slider = container.querySelector('.progress-slider');
            slider.focus();
        }
    }

    // Toggle progress input for table view
    function toggleProgressInputTable(element, projectId) {
        const container = element.parentElement.querySelector('.progress-input-container');
        container.classList.toggle('active');
        if (container.classList.contains('active')) {
            const slider = container.querySelector('.progress-slider');
            slider.focus();
        }
    }

    // Update progress value display
    function updateProgressValue(slider) {
        const container = slider.closest('.progress-input-container');
        const valueSpan = container.querySelector('.progress-value');
        valueSpan.textContent = slider.value + '%';
    }

    // Save progress
    function saveProgress(button, projectId) {
        const container = button.closest('.progress-input-container');
        const slider = container.querySelector('.progress-slider');
        const progress = parseInt(slider.value);

        fetch(`/admin/api/projects/${projectId}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ progress: progress })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Find the progress bar - it could be in card or table view
                let progressBar = null;
                let progressFill = null;
                
                // Try to find in card view (progress bar is before the input container)
                let prevElement = container.previousElementSibling;
                if (prevElement && prevElement.classList.contains('progress-bar')) {
                    progressBar = prevElement;
                } else {
                    // Try to find in table view (progress bar is in a parent div)
                    const parentDiv = container.parentElement;
                    if (parentDiv) {
                        progressBar = parentDiv.querySelector('.progress-bar');
                    }
                }
                
                if (progressBar) {
                    progressFill = progressBar.querySelector('.progress-fill');
                    if (progressFill) {
                        progressFill.style.width = progress + '%';
                    }
                }
                
                // Update status if auto-completed
                if (data.status === 'completed') {
                    let statusDropdown = null;
                    // Try card view first
                    const card = container.closest('.progress-card');
                    if (card) {
                        statusDropdown = card.querySelector('.status-dropdown');
                    } else {
                        // Try table view
                        const row = container.closest('tr');
                        if (row) {
                            statusDropdown = row.querySelector('.status-dropdown');
                        }
                    }
                    
                    if (statusDropdown) {
                        statusDropdown.value = 'completed';
                    }
                }
                
                // Close the input
                cancelProgressEdit(button);
                
                // Show success message
                showNotification('Progress updated successfully!', 'success');
            } else {
                showNotification(data.message || 'Error updating progress', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating progress: ' + error.message, 'error');
        });
    }

    // Cancel progress edit
    function cancelProgressEdit(button) {
        const container = button.closest('.progress-input-container');
        container.classList.remove('active');
    }

    // Update project status
    function updateProjectStatus(select) {
        const projectId = select.getAttribute('data-project-id');
        const status = select.value;

        fetch(`/admin/api/projects/${projectId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Project status updated successfully!', 'success');
                // Optionally reload the page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating status', 'error');
        });
    }

    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            ${type === 'success' ? 'background: #dcfce7; color: #15803d; border: 1px solid #86efac;' : 'background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;'}
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
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

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);

    // Load saved view preference on page load - default to table view
    window.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('projectsView') || 'table';
        switchView(savedView);
    });
</script>
@endsection
