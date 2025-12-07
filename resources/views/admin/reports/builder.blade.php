@extends('layouts.app')

@section('page-title', 'Report Builder')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(255, 179, 217, 0.15) 100%);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(179, 255, 179, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);
        margin-bottom: 32px;
    }

    .header-left h1 {
        font-size: 26px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .builder-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .form-section {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section h3 {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-item input[type="checkbox"] {
        width: auto;
    }

    .checkbox-item label {
        margin: 0;
        font-weight: 500;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #0F172A;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #f9fafb;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
</style>

<!-- Header Section -->
<div class="header-section">
    <div class="header-left">
        <h1>🛠️ Custom Report Builder</h1>
        <p>Create custom reports with selected metrics and filters</p>
    </div>
</div>

<!-- Builder Form -->
<div class="builder-container">
    <form method="POST" action="{{ route('admin.reports.store-custom') }}">
        @csrf

        <!-- Basic Information -->
        <div class="form-section">
            <h3>📋 Report Information</h3>
            
            <div class="form-group">
                <label for="name">Report Name *</label>
                <input type="text" id="name" name="name" required placeholder="Enter report name">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Enter report description"></textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_template" value="1">
                    Save as Template
                </label>
            </div>
        </div>

        <!-- Metrics Selection -->
        <div class="form-section">
            <h3>📊 Select Metrics *</h3>
            <div class="checkbox-group">
                @foreach($availableMetrics as $metric)
                    <div class="checkbox-item">
                        <input type="checkbox" id="metric_{{ $metric }}" name="metrics[]" value="{{ $metric }}">
                        <label for="metric_{{ $metric }}">{{ ucfirst(str_replace('_', ' ', $metric)) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filters -->
        <div class="form-section">
            <h3>🔍 Filters</h3>
            
            <div class="form-group">
                <label for="user_id">User</label>
                <select id="user_id" name="filters[user_id]">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="project_id">Project</label>
                <select id="project_id" name="filters[project_id]">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date_from">From Date</label>
                <input type="date" id="date_from" name="filters[date_from]">
            </div>

            <div class="form-group">
                <label for="date_to">To Date</label>
                <input type="date" id="date_to" name="filters[date_to]">
            </div>
        </div>

        <!-- Grouping -->
        <div class="form-section">
            <h3>📈 Grouping</h3>
            <div class="checkbox-group">
                @foreach($availableGrouping as $grouping)
                    <div class="checkbox-item">
                        <input type="checkbox" id="grouping_{{ $grouping }}" name="grouping[]" value="{{ $grouping }}">
                        <label for="grouping_{{ $grouping }}">{{ ucfirst(str_replace('_', ' ', $grouping)) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Branding -->
        <div class="form-section">
            <h3>🎨 Branding</h3>
            
            <div class="form-group">
                <label for="primary_color">Primary Color</label>
                <input type="color" id="primary_color" name="branding[primary_color]" value="#2563EB">
            </div>

            <div class="form-group">
                <label for="header_color">Header Color</label>
                <input type="color" id="header_color" name="branding[header_color]" value="#f2f2f2">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-primary">✓ Create Report</button>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </form>
</div>

@endsection
