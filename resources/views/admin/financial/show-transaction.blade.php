@extends('layouts.app')

@section('page-title', 'Transaction Details')

@section('content')
<style>
    .transaction-detail {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #E5E7EB;
    }

    .detail-title {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-approved {
        background: #DCFCE7;
        color: #15803D;
    }

    .status-completed {
        background: #DCFCE7;
        color: #15803D;
    }

    .status-rejected {
        background: #FEE2E2;
        color: #991B1B;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-item {
        padding: 15px;
        background: #F9FAFB;
        border-radius: 10px;
        border-left: 4px solid #003DA5;
    }

    .detail-label {
        font-size: 12px;
        color: #6B7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 16px;
        color: #1F2937;
        font-weight: 600;
    }

    .amount-income {
        color: #10B981;
    }

    .amount-expense {
        color: #EF4444;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #E5E7EB;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #E5E7EB;
        color: #1F2937;
    }

    .btn-secondary:hover {
        background: #D1D5DB;
    }

    .btn-danger {
        background: #EF4444;
        color: white;
    }

    .btn-danger:hover {
        background: #DC2626;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #10B981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .notes-section {
        background: #F9FAFB;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border-left: 4px solid #003DA5;
    }

    .notes-title {
        font-size: 14px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 10px;
    }

    .notes-content {
        color: #4B5563;
        line-height: 1.6;
    }
</style>

<div class="transaction-detail">
    <div class="detail-header">
        <div>
            <div class="detail-title">
                @if($transaction->type === 'income')
                    📥 Income Transaction
                @else
                    📤 Expense Transaction
                @endif
            </div>
        </div>
        <span class="status-badge status-{{ $transaction->status }}">
            {{ ucfirst($transaction->status) }}
        </span>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Transaction Type</div>
            <div class="detail-value">{{ ucfirst($transaction->type) }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Category</div>
            <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $transaction->category)) }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Amount</div>
            <div class="detail-value @if($transaction->type === 'income') amount-income @else amount-expense @endif">
                @if($transaction->type === 'income')
                    +${{ number_format($transaction->amount, 2) }}
                @else
                    -${{ number_format($transaction->amount, 2) }}
                @endif
            </div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Currency</div>
            <div class="detail-value">{{ $transaction->currency }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Account</div>
            <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $transaction->account)) }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Transaction Date</div>
            <div class="detail-value">{{ $transaction->transaction_date->format('d M Y') }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Description</div>
            <div class="detail-value">{{ $transaction->description }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Created By</div>
            <div class="detail-value">{{ $transaction->createdBy->name ?? 'N/A' }}</div>
        </div>

        @if($transaction->project)
        <div class="detail-item">
            <div class="detail-label">Project</div>
            <div class="detail-value">{{ $transaction->project->name }}</div>
        </div>
        @endif

        @if($transaction->approvedBy)
        <div class="detail-item">
            <div class="detail-label">Approved By</div>
            <div class="detail-value">{{ $transaction->approvedBy->name }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Approved At</div>
            <div class="detail-value">{{ $transaction->approved_at->format('d M Y H:i') }}</div>
        </div>
        @endif
    </div>

    @if($transaction->notes)
    <div class="notes-section">
        <div class="notes-title">📝 Notes</div>
        <div class="notes-content">{{ $transaction->notes }}</div>
    </div>
    @endif

    <div class="action-buttons">
        <a href="{{ route('admin.financial.index') }}" class="btn btn-secondary">
            ← Back to Dashboard
        </a>

        @if($transaction->status === 'pending')
            <form method="POST" action="{{ route('admin.financial.approveTransaction', $transaction) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success">✓ Approve</button>
            </form>

            <form method="POST" action="{{ route('admin.financial.rejectTransaction', $transaction) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">✕ Reject</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.financial.destroyTransaction', $transaction) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Delete</button>
        </form>
    </div>
</div>
@endsection
