@extends('layouts.app')

@section('page-title', 'Create New Project')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 30px; padding: 0 20px;">
        <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Create New Project</h1>
        <p style="color: #6B7280; font-size: 14px;">Set up a new project and assign team members to get started</p>
    </div>

    <form method="POST" action="/admin/projects" id="projectForm">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px; padding: 0 20px;">
            
            <!-- Main Form Column -->
            <div>
                <!-- Card: Project Details -->
                <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; color: #0F172A; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        📋 Project Details
                    </h3>

                    <div class="form-group">
                        <label for="name" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                            <span>📝</span> Project Name *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g., Website Redesign, Mobile App v2.0" style="width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: all 0.3s; background-color: #f9fafb;">
                        @error('name')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="status" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                            <span>🎯</span> Project Status *
                        </label>
                        <select id="status" name="status" required onchange="updateProgressColor()" style="width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 14px; background-color: #f9fafb; color: #0F172A;">
                            <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>🟦 Planning</option>
                            <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>🟩 In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>🟢 Completed</option>
                            <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>🟨 On Hold</option>
                        </select>
                        @error('status')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label for="start_date" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                                <span>📅</span> Start Date *
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required style="width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 14px; background-color: #f9fafb;">
                            @error('start_date')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="due_date" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                                <span>🎯</span> Due Date
                            </label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" style="width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 14px; background-color: #f9fafb;">
                            @error('due_date')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div style="margin-top: 16px;">
                        <label style="display: flex; align-items: center; gap: 6px; margin-bottom: 12px; font-weight: 600; color: #0F172A; font-size: 14px;">
                            <span>⚡</span> Quick Due Date Options
                        </label>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="button" class="quick-date-btn" onclick="setDueDate(1)" data-days="1" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s; hover:border-color: #2563EB;">1 Day</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(2)" data-days="2" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s;">2 Days</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(3)" data-days="3" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s;">3 Days</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(7)" data-days="7" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s;">1 Week</button>
                            <button type="button" class="quick-date-btn" onclick="setDueDate(14)" data-days="14" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s;">2 Weeks</button>
                        </div>
                    </div>
                </div>

                <!-- Card: Description -->
                <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 24px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; color: #0F172A; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        📝 Description
                    </h3>
                    <div class="form-group">
                        <textarea id="description" name="description" placeholder="Add project goals, scope, key requirements, and important notes…" style="width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 14px; font-family: inherit; min-height: 120px; resize: vertical; background-color: #f9fafb; color: #0F172A;">{{ old('description') }}</textarea>
                        @error('description')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); color: white; padding: 12px 28px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">✨ Create Project</button>
                    <a href="/admin/projects" style="background-color: white; color: #0F172A; padding: 12px 28px; border-radius: 8px; border: 1.5px solid #e5e7eb; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; text-decoration: none; display: inline-block;">Cancel</a>
                </div>
            </div>

            <!-- Sidebar: Progress & Team -->
            <div>
                <!-- Card: Progress -->
                <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 20px; margin-bottom: 20px; position: sticky; top: 20px;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        📊 Progress
                    </h3>
                    
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <input type="range" id="progress" name="progress" min="0" max="100" value="{{ old('progress', 0) }}" required onchange="updateProgressDisplay()" style="flex: 1; accent-color: #2563EB; cursor: pointer; height: 6px;">
                        <span id="progressValue" style="min-width: 45px; text-align: center; font-weight: 700; color: #2563EB; font-size: 16px;">0%</span>
                    </div>
                    
                    <div id="progressBar" style="width: 100%; height: 10px; background-color: #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 12px;">
                        <div id="progressFill" style="width: 0%; height: 100%; background: linear-gradient(90deg, #DC2626, #F59E0B, #06B6D4, #16A34A); transition: width 0.3s; border-radius: 10px;"></div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; font-size: 11px; color: #9ca3af;">
                        <span>0%</span>
                        <span>25%</span>
                        <span>50%</span>
                        <span>75%</span>
                        <span>100%</span>
                    </div>
                    @error('progress')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 8px;">{{ $message }}</span>@enderror
                </div>

                <!-- Card: Team Members -->
                <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 20px;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 600; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        👥 Team Members
                    </h3>
                    
                    <div id="selectedTeamContainer" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; min-height: 40px;">
                        <span style="color: #9ca3af; font-size: 13px;">No members selected yet</span>
                    </div>
                    
                    <select id="team_members" name="team_members[]" multiple onchange="updateTeamDisplay()" style="width: 100%; padding: 10px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-family: inherit; background-color: #f9fafb; min-height: 120px;">
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}" {{ in_array($member->id, old('team_members', [])) ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->role }})
                            </option>
                        @endforeach
                    </select>
                    
                    <small style="color: #6B7280; display: block; margin-top: 8px; font-size: 12px;">👉 Hold Ctrl/Cmd to select multiple</small>
                    @error('team_members')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 8px;">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .quick-date-btn {
        padding: 8px 16px;
        border: 2px solid #e5e7eb;
        background-color: white;
        color: #0F172A;
        border-radius: 20px;
        cursor: pointer;
        font-size: 13px;
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
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
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

    .team-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #f0f9ff;
        border: 1px solid #bfdbfe;
        color: #2563EB;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
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

    function updateTeamDisplay() {
        const select = document.getElementById('team_members');
        const container = document.getElementById('selectedTeamContainer');
        const selectedOptions = Array.from(select.selectedOptions);
        
        if (selectedOptions.length === 0) {
            container.innerHTML = '<span style="color: #9ca3af; font-size: 13px;">No members selected yet</span>';
        } else {
            container.innerHTML = selectedOptions.map(option => {
                return `<span class="team-badge">${option.text.split('(')[0].trim()} ✓</span>`;
            }).join('');
        }
    }

    function updateProgressColor() {
        updateProgressDisplay();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateProgressDisplay();
        updateTeamDisplay();
    });

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
