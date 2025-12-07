@extends('layouts.app')

@section('page-title', 'Financial Invoices')

@section('content')
<style>
    .invoice-table {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .invoice-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table thead {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-bottom: 2px solid #e5e7eb;
    }

    .invoice-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #0F172A;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .invoice-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13px;
    }

    .invoice-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-badge.draft {
        background-color: #E5E7EB;
        color: #374151;
    }

    .status-badge.sent {
        background-color: #FEF3C7;
        color: #92400E;
    }

    .status-badge.paid {
        background-color: #DCFCE7;
        color: #15803D;
    }

    .status-badge.overdue {
        background-color: #FEE2E2;
        color: #991B1B;
    }

    .status-badge.cancelled {
        background-color: #E9D5FF;
        color: #6B21A8;
    }

    .amount {
        font-weight: 700;
        font-size: 14px;
        color: #0F172A;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .action-btn.send {
        background-color: #FEF3C7;
        color: #92400E;
    }

    .action-btn.send:hover {
        background-color: #FDE68A;
    }

    .action-btn.paid {
        background-color: #DCFCE7;
        color: #15803D;
    }

    .action-btn.paid:hover {
        background-color: #BBFBEE;
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
    <h1>📄 Financial Invoices</h1>
    <a href="{{ route('admin.financial.createInvoice') }}" class="create-btn">+ New Invoice</a>
</div>

<!-- Invoices Table -->
@if($invoices->count() > 0)
    <div class="invoice-table">
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Project</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->project?->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->issue_date->format('M d, Y') }}</td>
                        <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                        <td>
                            <span class="amount">
                                ${{ number_format($invoice->total_amount, 2) }}
                            </span>
                        </td>
                        <td><span class="status-badge {{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span></td>
                        <td>
                            <div class="action-buttons">
                                @if($invoice->status === 'draft')
                                    <form method="POST" action="{{ route('admin.financial.sendInvoice', $invoice) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn send">Send</button>
                                    </form>
                                @elseif($invoice->status === 'sent')
                                    <form method="POST" action="{{ route('admin.financial.markInvoicePaid', $invoice) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn paid">Mark Paid</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 24px;">
        {{ $invoices->links() }}
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">📄</div>
        <h3>No Invoices Yet</h3>
        <p>Create your first invoice to start billing clients and tracking payments.</p>
        <a href="{{ route('admin.financial.createInvoice') }}" class="create-btn">Create Invoice</a>
    </div>
@endif
@endsection
