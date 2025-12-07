@extends('layouts.app')

@section('page-title', 'Create Transaction')

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

    .type-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
    }

    .type-option {
        position: relative;
    }

    .type-option input[type="radio"] {
        display: none;
    }

    .type-option label {
        display: block;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
        margin: 0;
    }

    .type-option input[type="radio"]:checked + label {
        border-color: #2563EB;
        background-color: #f0f9ff;
        color: #2563EB;
        font-weight: 600;
    }

    .type-option label:hover {
        border-color: #2563EB;
        background-color: #f9fafb;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2>📝 Create New Transaction</h2>
        <p>Add a new income or expense transaction</p>

        <div class="info-box">
            <strong>💡 Tip:</strong> Transactions start in "Pending" status and require approval before being counted in financial reports.
        </div>

        <form method="POST" action="{{ route('admin.financial.storeTransaction') }}">
            @csrf

            <!-- Transaction Type -->
            <div class="form-group">
                <label>Transaction Type *</label>
                <div class="type-selector">
                    <div class="type-option">
                        <input type="radio" id="type_income" name="type" value="income" {{ old('type', 'expense') === 'income' ? 'checked' : '' }} required>
                        <label for="type_income">
                            <div style="font-size: 24px; margin-bottom: 8px;">💵</div>
                            <div style="font-weight: 600;">Income</div>
                            <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">Money received</div>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" id="type_expense" name="type" value="expense" {{ old('type', 'expense') === 'expense' ? 'checked' : '' }} required>
                        <label for="type_expense">
                            <div style="font-size: 24px; margin-bottom: 8px;">💸</div>
                            <div style="font-weight: 600;">Expense</div>
                            <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">Money spent</div>
                        </label>
                    </div>
                </div>
                @error('type')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="salary" {{ old('category') === 'salary' ? 'selected' : '' }}>👤 Salary</option>
                    <option value="equipment" {{ old('category') === 'equipment' ? 'selected' : '' }}>🖥️ Equipment</option>
                    <option value="software" {{ old('category') === 'software' ? 'selected' : '' }}>💻 Software</option>
                    <option value="travel" {{ old('category') === 'travel' ? 'selected' : '' }}>✈️ Travel</option>
                    <option value="utilities" {{ old('category') === 'utilities' ? 'selected' : '' }}>⚡ Utilities</option>
                    <option value="marketing" {{ old('category') === 'marketing' ? 'selected' : '' }}>📢 Marketing</option>
                    <option value="client_payment" {{ old('category') === 'client_payment' ? 'selected' : '' }}>💰 Client Payment</option>
                    <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>📌 Other</option>
                </select>
                @error('category')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description *</label>
                <input type="text" id="description" name="description" value="{{ old('description') }}" placeholder="Enter transaction description" required>
                @error('description')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Amount, Currency, and Account -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 16px;">
                <div class="form-group">
                    <label for="amount">Amount *</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" placeholder="0.00" step="0.01" min="0" required>
                    @error('amount')<small style="color: #DC2626;">{{ $message }}</small>@enderror
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
                <div class="form-group">
                    <label for="account">Account *</label>
                    <select id="account" name="account" required>
                        <option value="cash" {{ old('account', 'cash') === 'cash' ? 'selected' : '' }}>💵 Cash on Hand</option>
                        <option value="anz_expense" {{ old('account') === 'anz_expense' ? 'selected' : '' }}>🏧 ANZ Expense Account</option>
                        <option value="anz_savings" {{ old('account') === 'anz_savings' ? 'selected' : '' }}>💎 ANZ Savings Account</option>
                    </select>
                    @error('account')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
            </div>

            <!-- Transaction Date -->
            <div class="form-group">
                <label for="transaction_date">Transaction Date *</label>
                <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                @error('transaction_date')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Project -->
            <div class="form-group">
                <label for="project_id">Project (Optional)</label>
                <select id="project_id" name="project_id">
                    <option value="">Select a project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                @error('notes')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">✓ Create Transaction</button>
                <a href="{{ route('admin.financial.transactions') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
