@extends('layouts.app')

@section('page-title', 'Create New Project')

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

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(179, 255, 217, 0.1) 100%);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(179, 217, 255, 0.3);
        max-width: 500px;
        width: 90%;
        animation: slideUp 0.3s ease;
        border: 1px solid rgba(179, 217, 255, 0.3);
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(179, 255, 179, 0.3);
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        color: #0F172A;
        font-weight: 700;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #6B7280;
        transition: color 0.3s;
    }

    .close-btn:hover {
        color: #0F172A;
    }

    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .template-card {
        padding: 15px;
        border: 2px solid rgba(179, 217, 255, 0.4);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(179, 255, 217, 0.1) 100%);
    }

    .template-card:hover {
        border-color: var(--soft-blue);
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(179, 255, 217, 0.2) 100%);
        transform: translateY(-2px);
    }

    .template-card.selected {
        border-color: var(--soft-green);
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #0F172A;
    }

    .template-card-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .template-card-name {
        font-size: 13px;
        font-weight: 600;
    }

    .quick-project-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
        border: 1px solid rgba(179, 255, 179, 0.5);
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(179, 255, 179, 0.3);
    }

    .quick-project-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(179, 255, 179, 0.4);
    }

    .recurring-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid rgba(179, 217, 255, 0.4);
        border-radius: 10px;
        margin-bottom: 10px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(255, 255, 255, 0.5) 100%);
    }

    .recurring-option input[type="radio"] {
        cursor: pointer;
    }

    .recurring-option label {
        cursor: pointer;
        flex: 1;
        margin: 0;
    }

    .recurring-frequency {
        display: none;
        margin-top: 15px;
        padding: 15px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.15) 0%, rgba(230, 217, 255, 0.15) 100%);
        border-radius: 10px;
        border-left: 4px solid var(--soft-blue);
    }

    .recurring-frequency.show {
        display: block;
    }
</style>

<!-- Quick Project Modal -->
<div id="quickProjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>⚡ Quick Project</h2>
            <button class="close-btn" onclick="closeQuickProjectModal()">&times;</button>
        </div>
        
        <form id="quickProjectForm" method="POST" action="/admin/projects">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0F172A; font-size: 13px;">Project Name *</label>
                <input type="text" name="quick_name" id="quick_name" required placeholder="e.g., Website Update" style="width: 100%; padding: 10px 12px; border: 1.5px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0F172A; font-size: 13px;">Status</label>
                <select name="quick_status" style="width: 100%; padding: 10px 12px; border: 1.5px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                    <option value="planning">📋 Planning</option>
                    <option value="in-progress">⚙️ In Progress</option>
                    <option value="cancelled">❌ Cancelled</option>
                    <option value="archived">📦 Archived</option>
                    <option value="awaiting-input">📝 Awaiting Input</option>
                    <option value="not-started">Not Started</option>
                    <option value="testing">Testing</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0F172A; font-size: 13px;">Due Date</label>
                <select name="quick_due_date" style="width: 100%; padding: 10px 12px; border: 1.5px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                    <option value="1">Tomorrow</option>
                    <option value="3">In 3 Days</option>
                    <option value="7">In 1 Week</option>
                    <option value="14">In 2 Weeks</option>
                    <option value="30">In 1 Month</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 13px;">Recurring Project?</label>
                
                <div class="recurring-option">
                    <input type="radio" id="recurring_no" name="is_recurring" value="0" checked onchange="toggleRecurringOptions()">
                    <label for="recurring_no">One-time project</label>
                </div>

                <div class="recurring-option">
                    <input type="radio" id="recurring_yes" name="is_recurring" value="1" onchange="toggleRecurringOptions()">
                    <label for="recurring_yes">Recurring project</label>
                </div>

                <div id="recurringFrequency" class="recurring-frequency">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0F172A; font-size: 13px;">Repeat Every:</label>
                    <select name="recurring_frequency" style="width: 100%; padding: 10px 12px; border: 1.5px solid #2563EB; border-radius: 6px; font-size: 13px;">
                        <option value="weekly">📅 Weekly</option>
                        <option value="biweekly">📅 Every 2 Weeks</option>
                        <option value="monthly">📅 Monthly</option>
                        <option value="quarterly">📅 Quarterly</option>
                    </select>
                    <small style="display: block; margin-top: 8px; color: #6B7280; font-size: 12px;">
                        ℹ️ Recurring projects will be automatically created on schedule
                    </small>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; padding: 12px; background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); color: #15803d; border: 1px solid rgba(179, 255, 179, 0.5); border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 13px;">Create Project</button>
                <button type="button" onclick="closeQuickProjectModal()" style="flex: 1; padding: 12px; background: linear-gradient(135deg, rgba(230, 217, 255, 0.3) 0%, rgba(255, 255, 255, 0.5) 100%); color: #0F172A; border: 1px solid rgba(217, 179, 255, 0.4); border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 13px;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Template Selection Modal -->
<div id="templateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>📋 Select Template</h2>
            <button class="close-btn" onclick="closeTemplateModal()">&times;</button>
        </div>
        
        <p style="color: #6B7280; font-size: 13px; margin-bottom: 15px;">Choose a template to quickly create a project with pre-configured settings</p>

        <div class="template-grid" id="templateGrid">
            <div class="template-card" onclick="selectTemplate(this, 'blank')">
                <div class="template-card-icon">📝</div>
                <div class="template-card-name">Blank Project</div>
            </div>
            <div class="template-card" onclick="selectTemplate(this, 'web')">
                <div class="template-card-icon">🌐</div>
                <div class="template-card-name">Web Project</div>
            </div>
            <div class="template-card" onclick="selectTemplate(this, 'mobile')">
                <div class="template-card-icon">📱</div>
                <div class="template-card-name">Mobile App</div>
            </div>
            <div class="template-card" onclick="selectTemplate(this, 'design')">
                <div class="template-card-icon">🎨</div>
                <div class="template-card-name">Design Project</div>
            </div>
            <div class="template-card" onclick="selectTemplate(this, 'marketing')">
                <div class="template-card-icon">📢</div>
                <div class="template-card-name">Marketing Campaign</div>
            </div>
            <div class="template-card" onclick="selectTemplate(this, 'maintenance')">
                <div class="template-card-icon">🔧</div>
                <div class="template-card-name">Maintenance</div>
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="useSelectedTemplate()" style="flex: 1; padding: 12px; background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); color: #0c4a6e; border: 1px solid rgba(179, 217, 255, 0.5); border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 13px;">Use Template</button>
            <button type="button" onclick="closeTemplateModal()" style="flex: 1; padding: 12px; background: linear-gradient(135deg, rgba(230, 217, 255, 0.3) 0%, rgba(255, 255, 255, 0.5) 100%); color: #0F172A; border: 1px solid rgba(217, 179, 255, 0.4); border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 13px;">Cancel</button>
        </div>
    </div>
</div>

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 30px; padding: 24px 28px; background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(230, 217, 255, 0.15) 100%); border-radius: 20px; border: 1px solid rgba(179, 255, 179, 0.3); box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 26px; color: #0F172A; font-weight: 700; margin-bottom: 6px; letter-spacing: -0.5px; display: flex; align-items: center; gap: 12px;">
                    <span style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); padding: 10px; border-radius: 12px; font-size: 22px;">📁</span>
                    Create New Project
                </h1>
                <p style="color: #6B7280; font-size: 14px; margin: 0;">Quick and easy way to add a project — set up details and assign team members</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="button" class="quick-project-btn" onclick="openQuickProjectModal()">⚡ Quick Project</button>
                <button type="button" class="quick-project-btn" style="background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); color: #6b21a8; border-color: rgba(217, 179, 255, 0.5); box-shadow: 0 2px 8px rgba(217, 179, 255, 0.3);" onclick="openTemplateModal()">📋 Use Template</button>
            </div>
        </div>
    </div>

    <form method="POST" action="/admin/projects" id="projectForm">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px; padding: 0 20px;">
            
            <!-- Main Form Column -->
            <div>
                <!-- Card: Project Details -->
                <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 217, 255, 0.08) 100%); border-radius: 20px; border: 1px solid rgba(179, 217, 255, 0.3); box-shadow: 0 4px 16px rgba(179, 217, 255, 0.12); padding: 24px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; color: #0F172A; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%); padding: 8px; border-radius: 10px; font-size: 14px;">📋</span>
                        Project Details
                    </h3>

                    <div class="form-group">
                        <label for="name" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                            <span>📝</span> Project Name *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g., Website Redesign, Mobile App v2.0" style="width: 100%; padding: 12px 14px; border: 1.5px solid rgba(179, 217, 255, 0.4); border-radius: 10px; font-size: 14px; transition: all 0.3s; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 217, 255, 0.05) 100%);">
                        @error('name')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="status" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                            <span>🎯</span> Project Status *
                        </label>
                        <select id="status" name="status" required onchange="updateProgressColor()" style="width: 100%; padding: 12px 14px; border: 1.5px solid rgba(179, 217, 255, 0.4); border-radius: 10px; font-size: 14px; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 217, 255, 0.05) 100%); color: #0F172A;">
                            <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>📋 Planning</option>
                            <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>🚀 In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>⏸️ On Hold</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>📦 Archived</option>
                            <option value="awaiting-input" {{ old('status') == 'awaiting-input' ? 'selected' : '' }}>📝 Awaiting Input</option>
                            <option value="not-started" {{ old('status') == 'not-started' ? 'selected' : '' }}>🚫 Not Started</option>
                            <option value="testing" {{ old('status') == 'testing' ? 'selected' : '' }}>🧪 Testing</option>
                            <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>⚠️ Overdue</option>
                        </select>
                        @error('status')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label for="start_date" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                                <span>📅</span> Start Date *
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required style="width: 100%; padding: 12px 14px; border: 1.5px solid rgba(179, 255, 179, 0.4); border-radius: 10px; font-size: 14px; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 255, 179, 0.05) 100%);">
                            @error('start_date')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="due_date" style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px; font-weight: 600; color: #0F172A; font-size: 14px;">
                                <span>🎯</span> Due Date *
                            </label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" required style="width: 100%; padding: 12px 14px; border: 1.5px solid rgba(255, 217, 179, 0.4); border-radius: 10px; font-size: 14px; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 217, 179, 0.05) 100%);">
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
                <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 255, 217, 0.08) 100%); border-radius: 20px; border: 1px solid rgba(179, 255, 217, 0.3); box-shadow: 0 4px 16px rgba(179, 255, 217, 0.12); padding: 24px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; color: #0F172A; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); padding: 8px; border-radius: 10px; font-size: 14px;">📝</span>
                        Description
                    </h3>
                    <div class="form-group">
                        <textarea id="description" name="description" placeholder="Add project goals, scope, key requirements, and important notes…" style="width: 100%; padding: 12px 14px; border: 1.5px solid rgba(179, 255, 217, 0.4); border-radius: 10px; font-size: 14px; font-family: inherit; min-height: 120px; resize: vertical; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(179, 255, 217, 0.05) 100%); color: #0F172A;">{{ old('description') }}</textarea>
                        @error('description')<span style="color: #DC2626; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); color: #15803d; padding: 14px 28px; border-radius: 12px; border: 1px solid rgba(179, 255, 179, 0.5); font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(179, 255, 179, 0.3);">✨ Create Project</button>
                    <a href="/admin/projects" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(230, 217, 255, 0.2) 100%); color: #0F172A; padding: 14px 28px; border-radius: 12px; border: 1px solid rgba(217, 179, 255, 0.4); font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; text-decoration: none; display: inline-block;">Cancel</a>
                </div>
            </div>

            <!-- Sidebar: Progress & Team -->
            <div>
                <!-- Card: Progress -->
                <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 217, 179, 0.1) 100%); border-radius: 20px; border: 1px solid rgba(255, 217, 179, 0.3); box-shadow: 0 4px 16px rgba(255, 217, 179, 0.12); padding: 20px; margin-bottom: 20px; position: sticky; top: 20px;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%); padding: 8px; border-radius: 10px; font-size: 14px;">📊</span>
                        Progress
                    </h3>
                    
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <input type="range" id="progress" name="progress" min="0" max="100" value="{{ old('progress', 0) }}" required onchange="updateProgressDisplay()" style="flex: 1; accent-color: var(--soft-green); cursor: pointer; height: 6px;">
                        <span id="progressValue" style="min-width: 45px; text-align: center; font-weight: 700; color: #15803d; font-size: 16px; background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); padding: 4px 10px; border-radius: 8px;">0%</span>
                    </div>
                    
                    <div id="progressBar" style="width: 100%; height: 10px; background-color: rgba(179, 217, 255, 0.3); border-radius: 10px; overflow: hidden; margin-bottom: 12px;">
                        <div id="progressFill" style="width: 0%; height: 100%; background: linear-gradient(90deg, var(--soft-pink), var(--soft-orange), var(--soft-blue), var(--soft-green)); transition: width 0.3s; border-radius: 10px;"></div>
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
                <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(217, 179, 255, 0.1) 100%); border-radius: 20px; border: 1px solid rgba(217, 179, 255, 0.3); box-shadow: 0 4px 16px rgba(217, 179, 255, 0.12); padding: 20px;">
                    <h3 style="font-size: 15px; color: #0F172A; font-weight: 700; margin-bottom: 14px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%); padding: 8px; border-radius: 10px; font-size: 14px;">👥</span>
                        Team Members
                    </h3>
                    
                    <div id="selectedTeamContainer" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; min-height: 40px;">
                        <span style="color: #9ca3af; font-size: 13px;">No members selected yet</span>
                    </div>
                    
                    <select id="team_members" name="team_members[]" multiple onchange="updateTeamDisplay()" style="width: 100%; padding: 10px; border: 1.5px solid rgba(217, 179, 255, 0.4); border-radius: 10px; font-size: 13px; font-family: inherit; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(230, 217, 255, 0.08) 100%); min-height: 120px;">
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
        border: 2px solid rgba(179, 217, 255, 0.4);
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(255, 255, 255, 0.5) 100%);
        color: #0F172A;
        border-radius: 20px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .quick-date-btn:hover {
        border-color: var(--soft-blue);
        background: linear-gradient(135deg, var(--soft-blue) 0%, rgba(179, 217, 255, 0.5) 100%);
        color: #0c4a6e;
        transform: translateY(-1px);
    }

    .quick-date-btn.active {
        border-color: var(--soft-green);
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
        box-shadow: 0 2px 8px rgba(179, 255, 179, 0.4);
    }

    select[multiple] {
        padding: 10px;
        border: 1px solid rgba(217, 179, 255, 0.4);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(230, 217, 255, 0.1) 100%);
    }

    select[multiple]:focus {
        outline: none;
        border-color: var(--soft-purple);
        box-shadow: 0 0 0 3px rgba(217, 179, 255, 0.2);
    }

    select[multiple] option {
        padding: 8px;
    }

    select[multiple] option:checked {
        background: linear-gradient(var(--soft-purple), var(--soft-purple));
        background-color: var(--soft-purple) !important;
        color: #0F172A;
    }

    .team-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, var(--soft-lavender) 0%, var(--soft-purple) 100%);
        border: 1px solid rgba(217, 179, 255, 0.4);
        color: #6b21a8;
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

    // Quick Project Modal Functions
    function openQuickProjectModal() {
        document.getElementById('quickProjectModal').classList.add('show');
    }

    function closeQuickProjectModal() {
        document.getElementById('quickProjectModal').classList.remove('show');
    }

    function toggleRecurringOptions() {
        const isRecurring = document.getElementById('recurring_yes').checked;
        const recurringFrequency = document.getElementById('recurringFrequency');
        if (isRecurring) {
            recurringFrequency.classList.add('show');
        } else {
            recurringFrequency.classList.remove('show');
        }
    }

    // Template Modal Functions
    let selectedTemplate = 'blank';

    function openTemplateModal() {
        document.getElementById('templateModal').classList.add('show');
    }

    function closeTemplateModal() {
        document.getElementById('templateModal').classList.remove('show');
    }

    function selectTemplate(element, templateId) {
        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('selected');
        });
        element.classList.add('selected');
        selectedTemplate = templateId;
    }

    function useSelectedTemplate() {
        const templateConfigs = {
            'blank': {
                name: 'New Project',
                status: 'planning'
            },
            'web': {
                name: 'Web Development Project',
                status: 'planning',
                description: 'Frontend & Backend Development\n- Design & Prototyping\n- Development\n- Testing\n- Deployment'
            },
            'mobile': {
                name: 'Mobile App Development',
                status: 'planning',
                description: 'Mobile Application Development\n- Requirements & Design\n- Development (iOS/Android)\n- Testing & QA\n- App Store Submission'
            },
            'design': {
                name: 'Design Project',
                status: 'planning',
                description: 'Design & Creative Work\n- Concept & Ideation\n- Design Mockups\n- Client Review & Revisions\n- Final Deliverables'
            },
            'marketing': {
                name: 'Marketing Campaign',
                status: 'planning',
                description: 'Marketing Campaign\n- Strategy & Planning\n- Content Creation\n- Campaign Launch\n- Analytics & Reporting'
            },
            'maintenance': {
                name: 'Maintenance & Support',
                status: 'in-progress',
                description: 'Ongoing Maintenance\n- Bug Fixes\n- Performance Optimization\n- Security Updates\n- Feature Enhancements'
            }
        };

        const config = templateConfigs[selectedTemplate];
        document.getElementById('name').value = config.name;
        document.getElementById('status').value = config.status;
        if (config.description) {
            document.getElementById('description').value = config.description;
        }

        closeTemplateModal();
        document.getElementById('name').focus();
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const quickModal = document.getElementById('quickProjectModal');
        const templateModal = document.getElementById('templateModal');
        
        if (event.target === quickModal) {
            closeQuickProjectModal();
        }
        if (event.target === templateModal) {
            closeTemplateModal();
        }
    });

    // Handle quick project form submission
    document.getElementById('quickProjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('quick_name').value;
        const status = document.querySelector('select[name="quick_status"]').value;
        const daysToAdd = parseInt(document.querySelector('select[name="quick_due_date"]').value);
        const isRecurring = document.querySelector('input[name="is_recurring"]:checked').value;
        const frequency = document.querySelector('select[name="recurring_frequency"]').value;

        // Calculate due date
        const today = new Date();
        const dueDate = new Date(today);
        dueDate.setDate(dueDate.getDate() + daysToAdd);

        // Fill in the main form
        document.getElementById('name').value = name;
        document.getElementById('status').value = status;
        document.getElementById('start_date').value = today.toISOString().split('T')[0];
        document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];

        // Add hidden fields for recurring projects
        if (isRecurring === '1') {
            const recurringInput = document.createElement('input');
            recurringInput.type = 'hidden';
            recurringInput.name = 'is_recurring';
            recurringInput.value = '1';
            document.getElementById('projectForm').appendChild(recurringInput);

            const frequencyInput = document.createElement('input');
            frequencyInput.type = 'hidden';
            frequencyInput.name = 'recurring_frequency';
            frequencyInput.value = frequency;
            document.getElementById('projectForm').appendChild(frequencyInput);
        }

        closeQuickProjectModal();
        document.getElementById('projectForm').submit();
    });
</script>
@endsection
