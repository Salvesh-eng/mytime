@extends('layouts.app')

@section('page-title', 'Edit Project')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card">
        <h2>Edit Project</h2>
        
        <form method="POST" action="/admin/projects/{{ $project->id }}" id="projectForm">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label for="name">Project Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required autofocus>
                        @error('name')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Project Status *</label>
                        <select id="status" name="status" required onchange="updateProgressColor()">
                            <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>📋 Planning</option>
                            <option value="in-progress" {{ old('status', $project->status) == 'in-progress' ? 'selected' : '' }}>🚀 In Progress</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="on-hold" {{ old('status', $project->status) == 'on-hold' ? 'selected' : '' }}>⏸️ On Hold</option>
                        </select>
                        @error('status')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date">Start Date *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                            <button type="button" class="quick-date-btn" onclick="setDueDate(1)" data-days="1">1 Day</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(2)" data-days="2">2 Days</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(3)" data-days="3">3 Days</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(7)" data-days="7">1 Week</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(14)" data-days="14">2 Weeks</button>
                        </div>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}">
                        @error('due_date')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="progress">Progress *</label>
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                            <input type="range" id="progress" name="progress" min="0" max="100" value="{{ old('progress', $project->progress) }}" required onchange="updateProgressDisplay()" style="flex: 1;">
                            <span id="progressValue" style="min-width: 40px; text-align: center; font-weight: 600; color: #2563EB;">{{ old('progress', $project->progress) }}%</span>
                        </div>
                        <div id="progressBar" style="width: 100%; height: 12px; background-color: #e5e7eb; border-radius: 6px; overflow: hidden;">
                            <div id="progressFill" style="width: {{ old('progress', $project->progress) }}%; height: 100%; background: linear-gradient(90deg, #DC2626, #F59E0B, #06B6D4, #16A34A); transition: width 0.3s; border-radius: 6px;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 11px; color: #6B7280; margin-top: 5px;">
                            <span>0%</span>
                            <span>25%</span>
                            <span>50%</span>
                            <span>75%</span>
                            <span>100%</span>
                        </div>
                        @error('progress')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="team_members">Team Members</label>
                        <select id="team_members" name="team_members[]" multiple style="min-height: 120px;">
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}" {{ in_array($member->id, old('team_members', $selectedTeamMembers)) ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->role }})
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #6B7280; display: block; margin-top: 5px;">Hold Ctrl/Cmd to select multiple members</small>
                        @error('team_members')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter project description...">{{ old('description', $project->description) }}</textarea>
                @error('description')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="/admin/projects" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .quick-date-btn {
        padding: 8px 12px;
        border: 2px solid #e5e7eb;
        background-color: white;
        color: #0F172A;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .quick-date-btn:hover {
        border-color: #2563EB;
        background-color: #f0f9ff;
        color: #2563EB;
    }

    .quick-date-btn.active {
        border-color: #2563EB;
        background-color: #2563EB;
        color: white;
    }

    select[multiple] {
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
    }

    select[multiple]:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    select[multiple] option {
        padding: 8px;
    }

    select[multiple] option:checked {
        background: linear-gradient(#2563EB, #2563EB);
        background-color: #2563EB !important;
        color: white;
    }
</style>

<script>
    function setDueDate(days) {
        const startDate = new Date(document.getElementById('start_date').value);
        const dueDate = new Date(startDate);
        dueDate.setDate(dueDate.getDate() + days);
        
        const year = dueDate.getFullYear();
        const month = String(dueDate.getMonth() + 1).padStart(2, '0');
        const day = String(dueDate.getDate()).padStart(2, '0');
        
        document.getElementById('due_date').value = `${year}-${month}-${day}`;

        // Update active button state
        document.querySelectorAll('.quick-date-btn').forEach(btn => {
            btn.classList.remove('active');
            if(btn.getAttribute('data-days') == days) {
                btn.classList.add('active');
            }
        });
    }

    function updateProgressDisplay() {
        const progress = document.getElementById('progress').value;
        document.getElementById('progressValue').textContent = progress + '%';
        document.getElementById('progressFill').style.width = progress + '%';
    }

    function updateProgressColor() {
        updateProgressDisplay();
    }

    // Initialize progress display on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateProgressDisplay();
    });

    // Update due date validation
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const dueDate = new Date(document.getElementById('due_date').value);
        
        if(dueDate < startDate) {
            document.getElementById('due_date').value = '';
            document.querySelectorAll('.quick-date-btn').forEach(btn => {
                btn.classList.remove('active');
            });
        }
    });
</script>
@endsection
