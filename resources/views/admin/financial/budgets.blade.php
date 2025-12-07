@extends('layouts.app')

@section('page-title', 'Financial Budgets')

@section('content')
<style>
    .budget-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .budget-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .budget-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .budget-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }

    .budget-header h3 {
        margin: 0;
        font-size: 16px;
        color: #0F172A;
        font-weight: 600;
        flex: 1;
    }

    .budget-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .budget-status.active {
        background-color: #CFFAFE;
        color: #164E63;
    }

    .budget-status.completed {
        background-color: #DCFCE7;
        color: #15803D;
    }

    .budget-status.archived {
        background-color: #E5E7EB;
        color: #374151;
    }

    .budget-info {
        margin-bottom: 16px;
    }

    .budget-info p {
        margin: 0 0 8px 0;
        font-size: 12px;
        color: #6B7280;
    }

    .budget-info p strong {
        color: #0F172A;
        font-weight: 600;
    }

    .budget-progress {
        margin-bottom: 12px;
    }

    .budget-progress-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .budget-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563EB, #06B6D4);
        transition: width 0.3s;
    }

    .budget-progress-text {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6B7280;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .header-section h1 {
        font-size: 28px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .create-btn {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6B7280;
        font-size: 14px;
        margin-bottom: 24px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<!-- Header -->
<div class="header-section">
    <h1>💼 Financial Budgets</h1>
    <a href="{{ route('admin.financial.createBudget') }}" class="create-btn">+ New Budget</a>
</div>

<!-- Budgets Grid -->
@if($budgets->count() > 0)
    <div class="budget-grid">
        @foreach($budgets as $budget)
            <div class="budget-card">
                <div class="budget-header">
                    <h3>{{ $budget->name }}</h3>
                    <span class="budget-status {{ $budget->status }}">{{ ucfirst($budget->status) }}</span>
                </div>

                <div class="budget-info">
                    @if($budget->project)
                        <p><strong>Project:</strong> {{ $budget->project->name }}</p>
                    @endif
                    <p><strong>Period:</strong> {{ $budget->start_date->format('M d, Y') }} - {{ $budget->end_date->format('M d, Y') }}</p>
                    <p><strong>Currency:</strong> {{ $budget->currency }}</p>
                </div>

                <div class="budget-progress">
                    <div class="budget-progress-bar">
                        <div class="budget-progress-fill" style="width: {{ $budget->budget_percentage }}%;"></div>
                    </div>
                    <div class="budget-progress-text">
                        <span>${{ number_format($budget->spent_amount, 2) }} spent</span>
                        <span>{{ $budget->budget_percentage }}%</span>
                    </div>
                </div>

                <div style="padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6B7280;">
                    <p style="margin: 0;">Allocated: <strong style="color: #0F172A;">${{ number_format($budget->allocated_amount, 2) }}</strong></p>
                    <p style="margin: 8px 0 0 0;">Remaining: <strong style="color: {{ $budget->remaining_budget > 0 ? '#16A34A' : '#DC2626' }};">${{ number_format($budget->remaining_budget, 2) }}</strong></p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 24px;">
        {{ $budgets->links() }}
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">💼</div>
        <h3>No Budgets Yet</h3>
        <p>Create your first budget to start tracking project expenses and allocations.</p>
        <a href="{{ route('admin.financial.createBudget') }}" class="create-btn">Create Budget</a>
    </div>
@endif
@endsection
