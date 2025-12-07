@extends('layouts.app')

@section('page-title', 'Create Budget')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 32px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .form-card h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-card p {
        color: #6B7280;
        font-size: 14px;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group small {
        display: block;
        color: #6B7280;
        font-size: 12px;
        margin-top: 6px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .form-actions button,
    .form-actions a {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .form-actions .btn-primary {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .form-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    .form-actions .btn-secondary {
        background-color: #e5e7eb;
        color: #0F172A;
    }

    .form-actions .btn-secondary:hover {
        background-color: #d1d5db;
    }

    .info-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #ecf8ff 100%);
        border: 1px solid #0ea5e9;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #0c4a6e;
        line-height: 1.6;
    }

    .info-box strong {
        color: #0369a1;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2>💼 Create New Budget</h2>
        <p>Set up a new budget for tracking expenses</p>

        <div class="info-box">
            <strong>💡 Tip:</strong> Budgets help you track spending against allocated amounts. You can create budgets for specific projects or general organizational expenses.
        </div>

        <form method="POST" action="{{ route('admin.financial.storeBudget') }}">
            @csrf

            <!-- Budget Name -->
            <div class="form-group">
                <label for="name">Budget Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., Q1 2025 Operations" required>
                @error('name')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Project -->
            <div class="form-group">
                <label for="project_id">Project (Optional)</label>
                <select id="project_id" name="project_id">
                    <option value="">General Budget</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Allocated Amount and Currency -->
            <div class="form-row">
                <div class="form-group">
                    <label for="allocated_amount">Allocated Amount *</label>
                    <input type="number" id="allocated_amount" name="allocated_amount" value="{{ old('allocated_amount') }}" placeholder="0.00" step="0.01" min="0" required>
                    @error('allocated_amount')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="currency">Currency *</label>
                    <select id="currency" name="currency" required>
                        <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                        <option value="CAD" {{ old('currency') === 'CAD' ? 'selected' : '' }}>CAD</option>
                        <option value="AUD" {{ old('currency') === 'AUD' ? 'selected' : '' }}>AUD</option>
                    </select>
                    @error('currency')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
            </div>

            <!-- Budget Period -->
            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="end_date">End Date *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Add budget details and notes...">{{ old('description') }}</textarea>
                @error('description')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">✓ Create Budget</button>
                <a href="{{ route('admin.financial.budgets') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
