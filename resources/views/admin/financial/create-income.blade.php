@extends('layouts.app')

@section('page-title', 'Add Income Transaction')

@section('content')
<style>
    :root {
        --primary: #FF6B35;
        --primary-light: #FFA500;
        --success: #FFD700;
        --danger: #DC143C;
        --warning: #FF8C00;
        --info: #FF4500;
        --dark: #1a0f0a;
        --light: #FFF8DC;
        --border: #FFE4B5;
    }

    .form-container {
        max-width: 600px;
        margin: 30px auto;
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-header h1 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
    }

    .form-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .invoice-number-display {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
        margin-bottom: 20px;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--dark);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-row.full {
        grid-template-columns: 1fr;
    }

    .button-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
    }

    .btn-secondary {
        background: white;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
        background: var(--primary);
        color: white;
    }

    .help-text {
        font-size: 12px;
        color: #6B7280;
        margin-top: 5px;
    }

    .error-message {
        color: var(--danger);
        font-size: 12px;
        margin-top: 5px;
    }

    .success-badge {
        display: inline-block;
        background: var(--success);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 10px;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1>📥 Add Income Transaction</h1>
        <p>Record a new income entry with auto-generated invoice number</p>
    </div>

    @if ($errors->any())
        <div style="background: #FEE2E2; border: 1px solid #FECACA; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #991b1b;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.financial.storeIncome') }}" method="POST">
        @csrf

        <!-- Invoice Number (Auto-generated) -->
        <div class="form-group">
            <label>Invoice Number <span class="success-badge">Auto-Generated</span></label>
            <div class="invoice-number-display">
                {{ $invoiceNumber }}
            </div>
            <input type="hidden" name="invoice_number" value="{{ $invoiceNumber }}">
            <p class="help-text">✓ Unique invoice number automatically generated for tracking</p>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label for="category">Category <span style="color: var(--danger);">*</span></label>
            <select id="category" name="category" required>
                <option value="">-- Select Category --</option>
                <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>💼 Salary</option>
                <option value="client_payment" {{ old('category') == 'client_payment' ? 'selected' : '' }}>💰 Client Payment</option>
                <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>🔧 Equipment</option>
                <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>💻 Software</option>
                <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>✈️ Travel</option>
                <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>⚡ Utilities</option>
                <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>📢 Marketing</option>
                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>📌 Other</option>
            </select>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description <span style="color: var(--danger);">*</span></label>
            <input type="text" id="description" name="description" placeholder="e.g., Monthly salary payment" value="{{ old('description') }}" required>
            <p class="help-text">Brief description of the income</p>
        </div>

        <!-- Amount and Currency -->
        <div class="form-row">
            <div class="form-group">
                <label for="amount">Amount <span style="color: var(--danger);">*</span></label>
                <input type="number" id="amount" name="amount" placeholder="0.00" step="0.01" min="0.01" value="{{ old('amount') }}" required>
            </div>
            <div class="form-group">
                <label for="currency">Currency <span style="color: var(--danger);">*</span></label>
                <select id="currency" name="currency" required>
                    <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                    <option value="FJD" {{ old('currency') == 'FJD' ? 'selected' : '' }}>FJD</option>
                </select>
            </div>
        </div>

        <!-- Account -->
        <div class="form-group">
            <label for="account">Account <span style="color: var(--danger);">*</span></label>
            <select id="account" name="account" required>
                <option value="">-- Select Account --</option>
                <option value="cash" {{ old('account') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                <option value="anz_expense" {{ old('account') == 'anz_expense' ? 'selected' : '' }}>💳 ANZ Expense Account</option>
                <option value="anz_savings" {{ old('account') == 'anz_savings' ? 'selected' : '' }}>🏦 ANZ Savings Account</option>
            </select>
        </div>

        <!-- Transaction Date -->
        <div class="form-group">
            <label for="transaction_date">Transaction Date <span style="color: var(--danger);">*</span></label>
            <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required>
        </div>

        <!-- Project (Optional) -->
        <div class="form-group">
            <label for="project_id">Project (Optional)</label>
            <select id="project_id" name="project_id">
                <option value="">-- No Project --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Notes -->
        <div class="form-group">
            <label for="notes">Notes (Optional)</label>
            <textarea id="notes" name="notes" placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-primary">
                <span>✓</span> Create Income
            </button>
            <a href="{{ route('admin.financial.index') }}" class="btn btn-secondary">
                <span>✕</span> Cancel
            </a>
        </div>
    </form>
</div>

@endsection
