@extends('layouts.app')

@section('page-title', 'Create Invoice')

@section('content')
<style>
    .form-container {
        max-width: 700px;
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
        min-height: 80px;
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

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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

    .section-divider {
        border-top: 2px solid #f3f4f6;
        margin: 24px 0;
        padding-top: 24px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2>📄 Create New Invoice</h2>
        <p>Generate a new invoice for billing clients</p>

        <div class="info-box">
            <strong>💡 Tip:</strong> Invoices start in "Draft" status. Send them to clients when ready, and mark as paid once payment is received.
        </div>

        <form method="POST" action="{{ route('admin.financial.storeInvoice') }}">
            @csrf

            <!-- Invoice Number -->
            <div class="form-group">
                <label for="invoice_number">Invoice Number *</label>
                <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', 'INV-' . date('Ymd') . '-') }}" placeholder="e.g., INV-20250101-001" required>
                <small>Must be unique</small>
                @error('invoice_number')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Project and Client -->
            <div class="form-row">
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

            <!-- Dates -->
            <div class="form-row">
                <div class="form-group">
                    <label for="issue_date">Issue Date *</label>
                    <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                    @error('issue_date')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date *</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                    @error('due_date')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
            </div>

            <!-- Amounts Section -->
            <div class="section-divider">
                <div class="section-title">💰 Invoice Amounts</div>
            </div>

            <!-- Subtotal and Tax -->
            <div class="form-row">
                <div class="form-group">
                    <label for="subtotal">Subtotal *</label>
                    <input type="number" id="subtotal" name="subtotal" value="{{ old('subtotal') }}" placeholder="0.00" step="0.01" min="0" required onchange="calculateTotal()">
                    @error('subtotal')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label for="tax_amount">Tax Amount</label>
                    <input type="number" id="tax_amount" name="tax_amount" value="{{ old('tax_amount', 0) }}" placeholder="0.00" step="0.01" min="0" onchange="calculateTotal()">
                    @error('tax_amount')<small style="color: #DC2626;">{{ $message }}</small>@enderror
                </div>
            </div>

            <!-- Total Display -->
            <div class="form-group">
                <label>Total Amount</label>
                <div style="padding: 12px 14px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px; font-weight: 700; color: #2563EB;">
                    $<span id="total-display">0.00</span>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="section-divider">
                <div class="section-title">📝 Additional Information</div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes">Invoice Notes</label>
                <textarea id="notes" name="notes" placeholder="Add any notes or special instructions...">{{ old('notes') }}</textarea>
                @error('notes')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Terms -->
            <div class="form-group">
                <label for="terms">Payment Terms</label>
                <textarea id="terms" name="terms" placeholder="e.g., Net 30, Due upon receipt...">{{ old('terms') }}</textarea>
                @error('terms')<small style="color: #DC2626;">{{ $message }}</small>@enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">✓ Create Invoice</button>
                <a href="{{ route('admin.financial.invoices') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateTotal() {
        const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
        const total = subtotal + tax;
        document.getElementById('total-display').textContent = total.toFixed(2);
    }

    // Calculate on page load
    window.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endsection
