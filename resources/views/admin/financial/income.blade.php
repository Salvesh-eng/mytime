@extends('layouts.app')

@section('page-title', 'Income Management')

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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .income-container {
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .back-button {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #FFB3D9 0%, #B3D9FF 100%);
        color: #333;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .form-section {
        background: white;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid var(--soft-blue);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--soft-blue);
        box-shadow: 0 0 0 3px rgba(179, 217, 255, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
        color: #333;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-small {
        padding: 8px 12px;
        font-size: 12px;
        margin-right: 6px;
        border-radius: 6px;
    }

    .btn-view {
        background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%);
        color: #333;
    }

    .btn-edit {
        background: linear-gradient(135deg, #B3FFB3 0%, #a5ffa5 100%);
        color: #333;
    }

    .btn-delete {
        background: linear-gradient(135deg, #FFB3D9 0%, #ffa5cc 100%);
        color: #333;
    }

    .btn-small:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .summary-card {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .summary-card-label {
        font-size: 11px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .summary-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 350px;
    }

    .toast {
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.4s ease-out;
        min-width: 280px;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .toast.hide {
        animation: slideOutRight 0.3s ease-in forwards;
    }

    .toast-success {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        border-left: 4px solid #15803d;
        color: #0F172A;
    }

    .toast-error {
        background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%);
        border-left: 4px solid #dc2626;
        color: #0F172A;
    }

    .toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .toast-message {
        font-size: 13px;
        opacity: 0.9;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        opacity: 0.6;
        padding: 0;
        line-height: 1;
        transition: opacity 0.2s;
    }

    .toast-close:hover {
        opacity: 1;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
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
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
        animation: slideUp 0.3s ease;
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
        border-bottom: 2px solid var(--soft-blue);
    }

    .modal-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: #333;
    }

    .modal-body {
        margin-bottom: 20px;
    }

    .modal-body p {
        margin: 12px 0;
        font-size: 14px;
        color: #555;
    }

    .modal-body strong {
        color: #333;
        font-weight: 600;
    }

    .modal-body .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: var(--soft-blue);
        color: #333;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .modal-footer .btn {
        padding: 10px 20px;
        font-size: 13px;
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #333;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .btn-confirm {
        background: var(--soft-pink);
        color: #333;
    }

    .btn-confirm:hover {
        background: #ffb3d9;
    }

    .card-table {
        display: grid;
        gap: 15px;
        margin-top: 20px;
    }

    .card-row {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #e8e8e8;
        border-radius: 12px;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .card-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #B3D9FF 0%, #B3FFB3 100%);
    }

    .card-row:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        border-color: #B3D9FF;
        transform: translateY(-2px);
    }

    .card-content {
        display: grid;
        gap: 12px;
        padding-left: 8px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
    }

    .card-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #555;
    }

    .card-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .card-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%);
        color: #0F172A;
        box-shadow: 0 2px 6px rgba(179, 217, 255, 0.3);
        text-transform: capitalize;
    }

    .card-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .card-actions .btn {
        padding: 10px 16px;
        font-size: 12px;
        margin: 0;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .card-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .chart-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .chart-title {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        text-align: center;
    }

    .chart-wrapper {
        position: relative;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 1200px) {
        .charts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .card-row {
            grid-template-columns: 1fr;
        }
        
        .card-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .page-header h1 {
            font-size: 24px;
        }
        
        .card-row {
            grid-template-columns: 1fr;
        }
        
        .modal-content {
            max-width: 90%;
        }
    }
</style>

<div class="toast-container" id="toastContainer"></div>

<!-- View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="viewModalTitle">Details</h2>
            <button class="modal-close" onclick="closeModal('viewModal')">×</button>
        </div>
        <div class="modal-body" id="viewModalBody"></div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Delete</h2>
            <button class="modal-close" onclick="closeModal('deleteModal')">×</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn btn-confirm" id="confirmDeleteBtn" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

<div class="income-container">
    <a href="/admin/financial" class="back-button">← Back to Dashboard</a>

    <div class="page-header">
        <h1>💰 Income Management</h1>
        <p>Track and manage all your income sources and transactions</p>
    </div>

    <!-- Income Summary Cards -->
    <div class="form-section" style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(179, 255, 179, 0.2) 100%);">
        <h2 class="section-title">📊 Income Summary</h2>
        <div class="summary-cards" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
            <div class="summary-card" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%);">
                <div class="summary-card-label">💵 Total Income</div>
                <div class="summary-card-value">${{ number_format($totalIncome ?? 0, 0) }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 4px;">All Time</div>
            </div>
            <div class="summary-card" style="background: linear-gradient(135deg, #B3FFB3 0%, #a5ffa5 100%);">
                <div class="summary-card-label">📅 This Month</div>
                <div class="summary-card-value">${{ number_format($thisMonthIncome ?? 0, 0) }}</div>
                <div style="font-size: 11px; color: {{ ($monthlyChange ?? 0) >= 0 ? '#16A34A' : '#DC2626' }}; margin-top: 4px; font-weight: 600;">
                    {{ ($monthlyChange ?? 0) >= 0 ? '↑' : '↓' }} {{ abs($monthlyChange ?? 0) }}% vs last month
                </div>
            </div>
            <div class="summary-card" style="background: linear-gradient(135deg, #FFD9B3 0%, #ffcca5 100%);">
                <div class="summary-card-label">📆 Last Month</div>
                <div class="summary-card-value">${{ number_format($lastMonthIncome ?? 0, 0) }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 4px;">{{ \Carbon\Carbon::now()->subMonth()->format('F') }}</div>
            </div>
            <div class="summary-card" style="background: linear-gradient(135deg, #D9B3FF 0%, #cca5ff 100%);">
                <div class="summary-card-label">📈 This Year</div>
                <div class="summary-card-value">${{ number_format($thisYearIncome ?? 0, 0) }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 4px;">{{ now()->year }}</div>
            </div>
            <div class="summary-card" style="background: linear-gradient(135deg, #FFB3D9 0%, #ffa5cc 100%);">
                <div class="summary-card-label">📊 Avg/Month</div>
                <div class="summary-card-value">${{ number_format($avgMonthlyIncome ?? 0, 0) }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 4px;">Average</div>
            </div>
            <div class="summary-card" style="background: linear-gradient(135deg, #B3FFD9 0%, #a5ffcc 100%);">
                <div class="summary-card-label">🎯 Top Source</div>
                <div class="summary-card-value" style="font-size: 18px;">{{ ucfirst(str_replace('_', ' ', $topIncomeSource->category ?? 'N/A')) }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 4px;">${{ number_format($topIncomeSource->total ?? 0, 0) }}</div>
            </div>
        </div>
    </div>

    <!-- Income Sources Section -->
    <div class="form-section" style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(179, 255, 179, 0.1) 100%); border-left: 5px solid #B3D9FF;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px solid #B3D9FF;">
            <h2 class="section-title" style="color: #0F172A; font-size: 20px; border-bottom: none; margin: 0;">💼 Income Sources</h2>
            <button type="button" class="btn btn-primary" onclick="toggleIncomeSourceForm()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 600; padding: 8px 16px; font-size: 12px;">
                <span id="toggleSourceBtnText">Show Form</span>
            </button>
        </div>
        <p style="color: #555; margin-bottom: 20px; font-size: 14px; font-weight: 500;">Add and manage your income sources to track all revenue streams</p>

        <div id="incomeSourceFormContainer" style="display: none;">
            <form id="incomeSourceForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="sourceName" style="color: #0F172A; font-weight: 700;">Income Source Name *</label>
                        <input type="text" id="sourceName" placeholder="e.g., Salary, Freelance Work" required>
                    </div>
                    <div class="form-group">
                        <label for="category" style="color: #0F172A; font-weight: 700;">Category *</label>
                        <select id="category" required>
                            <option value="">Select Category</option>
                            <option value="salary">💼 Salary</option>
                            <option value="client_payment">👥 Client Payment</option>
                            <option value="equipment">🖥️ Equipment/Sales</option>
                            <option value="software">💻 Software/Services</option>
                            <option value="travel">✈️ Travel/Reimbursement</option>
                            <option value="utilities">⚡ Utilities/Refund</option>
                            <option value="marketing">📢 Marketing/Bonus</option>
                            <option value="savings">🏦 Savings Transfer</option>
                            <option value="investment_return">📈 Investment Return</option>
                            <option value="bonus">🎁 Bonus</option>
                            <option value="freelance">💻 Freelance Work</option>
                            <option value="rental">🏠 Rental Income</option>
                            <option value="gift">🎀 Gift</option>
                            <option value="other">📌 Other Income</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type" style="color: #0F172A; font-weight: 700;">Type *</label>
                        <select id="type" required>
                            <option value="">Select Type</option>
                            <option value="recurring">🔄 Recurring</option>
                            <option value="variable">📊 Variable</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addIncomeSource()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 700; box-shadow: 0 4px 12px rgba(179, 217, 255, 0.3);">+ Add Income Source</button>
            </form>
        </div>

        <div class="card-table" id="incomeSourcesTable" style="display: none;"></div>
    </div>

    <!-- Monthly Budget Section -->
    <div class="form-section" style="background: linear-gradient(135deg, rgba(179, 255, 179, 0.1) 0%, rgba(179, 217, 255, 0.1) 100%); border-left: 5px solid #B3FFB3;">
        <h2 class="section-title" style="color: #0F172A; font-size: 20px; border-bottom: 3px solid #B3FFB3;">📅 Monthly Income Budget</h2>

        <form id="budgetForm">
            <div class="form-row" style="gap: 10px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="currentMonth" style="margin-bottom: 4px; font-size: 13px;">Current Month</label>
                    <input type="text" id="currentMonth" readonly style="background: #f5f5f5; cursor: not-allowed; padding: 8px; font-size: 13px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="monthlyBudget" style="margin-bottom: 4px; font-size: 13px;">Expected Budget *</label>
                    <input type="number" id="monthlyBudget" placeholder="$0.00" min="0" step="0.01" required style="padding: 8px; font-size: 13px;">
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="button" class="btn btn-primary" onclick="saveBudget()" style="padding: 8px 14px; font-size: 12px; margin-bottom: 0;">💾 Save Budget</button>
                </div>
            </div>
        </form>

        <!-- Budget Display Card -->
        <div id="budgetDisplayCard" style="display: none; margin-top: 20px;">
            <div class="card-row" style="grid-template-columns: 1fr auto; gap: 20px;">
                <div class="card-content">
                    <div class="card-title">💰 Current Budget</div>
                    <div class="card-meta">
                        <div class="card-meta-item">
                            <strong>Month:</strong> <span id="budgetMonth"></span>
                        </div>
                        <div class="card-meta-item">
                            <strong>Budget Amount:</strong> $<span id="budgetAmount"></span>
                        </div>
                    </div>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn btn-small btn-edit" onclick="editBudget()">✏️ Edit</button>
                    <button type="button" class="btn btn-small btn-delete" onclick="clearBudget()">🗑️ Clear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Income Transactions Section -->
    <div class="form-section" style="background: linear-gradient(135deg, rgba(179, 255, 179, 0.1) 0%, rgba(179, 217, 255, 0.1) 100%); border-left: 5px solid #B3FFB3;">
        <h2 class="section-title" style="color: #0F172A; font-size: 20px; border-bottom: 3px solid #B3FFB3;">📋 Income Transactions</h2>
        <p style="color: #555; margin-bottom: 20px; font-size: 14px; font-weight: 500;">Record your actual income received</p>

        <form id="incomeTrackingForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="trackingMonth">Month</label>
                    <input type="text" id="trackingMonth" readonly style="background: #f5f5f5; cursor: not-allowed;">
                </div>
                <div class="form-group">
                    <label for="incomeDate">Date *</label>
                    <input type="date" id="incomeDate" required>
                </div>
                <div class="form-group">
                    <label for="incomeSource">Income Source *</label>
                    <select id="incomeSource" required>
                        <option value="">Select Income Source</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="actualMonthlyIncome">Amount Received *</label>
                    <input type="number" id="actualMonthlyIncome" placeholder="$0.00" min="0" step="0.01" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="incomeDescription">Description (Optional)</label>
                    <input type="text" id="incomeDescription" placeholder="e.g., Monthly salary payment">
                </div>
                <div class="form-group">
                    <label for="accountType">Account *</label>
                    <select id="accountType" required>
                        <option value="">Select Account</option>
                        <option value="cash_on_hand">💵 Cash On Hand</option>
                        <option value="bank_expense">🏦 Bank Expense Account</option>
                        <option value="bank_savings">🏦 Bank Savings Account</option>
                        <option value="m_paisa">📱 M-Paisa 2999919</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="saveActualIncome()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 700; box-shadow: 0 4px 12px rgba(179, 217, 255, 0.3);">💾 Save Income Transaction</button>
        </form>

        <!-- Income Transactions Table -->
        <div style="margin-top: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px solid #B3FFB3;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">📊 Transaction History</h3>
                <button type="button" class="btn btn-primary" onclick="toggleTransactionHistory()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 600; padding: 8px 16px; font-size: 12px;">
                    <span id="toggleBtnText">Show Transactions</span>
                </button>
            </div>
            
            <div id="transactionHistoryContainer" style="display: none;">
                <div class="card-table" id="incomeTransactionsTable" style="display: none;"></div>
                <div id="noTransactionsMessage" style="text-align: center; padding: 40px 30px; color: #999; font-size: 14px; background: white; border-radius: 8px; border: 2px dashed #B3FFB3;">
                    <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                    No income transactions yet. Add one above to get started!
                </div>
                
                <!-- Pagination Controls -->
                <div id="paginationControls" style="display: none; margin-top: 20px; text-align: center; padding: 15px; background: white; border-radius: 8px;">
                    <button type="button" class="btn btn-primary" onclick="previousPage()" style="margin-right: 10px; background: linear-gradient(135deg, #B3FFB3 0%, #a5ffa5 100%); font-weight: 600;">← Previous</button>
                    <span id="pageInfo" style="margin: 0 15px; font-weight: 700; color: #0F172A; font-size: 14px;"></span>
                    <button type="button" class="btn btn-primary" onclick="nextPage()" style="margin-left: 10px; background: linear-gradient(135deg, #B3FFB3 0%, #a5ffa5 100%); font-weight: 600;">Next →</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Charts Section -->
    <div class="form-section">
        <h2 class="section-title">📈 Income Analytics</h2>
        
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-title">💰 Total Budgeted vs Actual</div>
                <div class="chart-wrapper" style="height: 180px;">
                    <canvas id="incomeSummaryChart" style="max-width: 100%; max-height: 180px;"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">🎯 Income Sources Breakdown</div>
                <div class="chart-wrapper" style="height: 180px;">
                    <canvas id="incomeSourcesBreakdownChart" style="max-width: 100%; max-height: 180px;"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">📊 Income Distribution by Category</div>
                <div class="chart-wrapper" style="height: 180px;">
                    <canvas id="incomeDistributionChart" style="max-width: 100%; max-height: 180px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let incomeSources = [];
    let budgetedIncome = new Array(12).fill(0);
    let actualIncome = @json($monthlyIncomeData['values'] ?? array_fill(0, 12, 0));
    let deleteItemId = null;
    let deleteItemType = null;
    
    // Real data from database
    const incomeByCategory = @json($incomeByCategory ?? []);
    const monthlyIncomeData = @json($monthlyIncomeData ?? ['months' => [], 'values' => []]);
    
    // Initialize actual income with monthly data
    if (monthlyIncomeData.values && monthlyIncomeData.values.length > 0) {
        actualIncome = monthlyIncomeData.values;
    }

    function showAlert(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icon = type === 'success' ? '✅' : '❌';
        const title = type === 'success' ? 'Success!' : 'Error';
        
        toast.innerHTML = `
            <span class="toast-icon">${icon}</span>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    function initializeCurrentMonth() {
        const today = new Date();
        const currentMonthIndex = today.getMonth();
        const currentMonthName = months[currentMonthIndex];
        
        document.getElementById('currentMonth').value = currentMonthName;
        document.getElementById('trackingMonth').value = currentMonthName;
        document.getElementById('incomeDate').valueAsDate = new Date();
    }

    function addIncomeSource() {
        const sourceName = document.getElementById('sourceName').value;
        const category = document.getElementById('category').value;
        const type = document.getElementById('type').value;

        if (!sourceName || !category || !type) {
            showAlert('Please fill in all fields', 'error');
            return;
        }

        fetch('/admin/api/financial/income-source', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                source_name: sourceName,
                category: category,
                type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('sourceName').value = '';
                document.getElementById('category').value = '';
                document.getElementById('type').value = '';
                loadIncomeSources();
                showAlert('Income source saved successfully!', 'success');
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error saving income source: ' + error.message, 'error');
        });
    }

    function loadIncomeSources() {
        return fetch('/admin/api/financial/income-sources', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                incomeSources = data.data.map(item => ({
                    id: item.id,
                    sourceName: item.description,
                    category: item.category,
                    type: item.notes ? JSON.parse(item.notes).income_type : 'recurring',
                    invoiceNumber: `INC-${item.id}`
                }));
                updateIncomeSourcesTable();
                populateIncomeSourceDropdown();
            }
        })
        .catch(error => console.error('Error loading income sources:', error));
    }

    function updateIncomeSourcesTable() {
        const table = document.getElementById('incomeSourcesTable');

        if (incomeSources.length === 0) {
            table.style.display = 'none';
            return;
        }

        table.style.display = 'grid';
        table.innerHTML = '';

        incomeSources.forEach(source => {
            const card = document.createElement('div');
            card.className = 'card-row';
            card.innerHTML = `
                <div class="card-content">
                    <div class="card-title">💼 ${source.sourceName}</div>
                    <div class="card-meta">
                        <div class="card-meta-item">
                            <span class="card-badge">${source.category}</span>
                        </div>
                        <div class="card-meta-item">
                            <span class="card-badge">${source.type}</span>
                        </div>
                        <div class="card-meta-item">
                            <span style="font-family: monospace; font-size: 11px; background: #f0f0f0; padding: 4px 8px; border-radius: 4px; color: #666;">${source.invoiceNumber}</span>
                        </div>
                    </div>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn btn-small btn-view" onclick="viewIncomeSource(${source.id})">👁️ View</button>
                    <button type="button" class="btn btn-small btn-edit" onclick="editIncomeSource(${source.id})">✏️ Edit</button>
                    <button type="button" class="btn btn-small btn-delete" onclick="openDeleteModal(${source.id}, 'source')">🗑️ Delete</button>
                </div>
            `;
            table.appendChild(card);
        });
    }

    function viewIncomeSource(id) {
        const source = incomeSources.find(s => s.id === id);
        if (source) {
            const body = document.getElementById('viewModalBody');
            body.innerHTML = `
                <p><strong>Source Name:</strong> ${source.sourceName}</p>
                <p><strong>Category:</strong> <span class="badge">${source.category}</span></p>
                <p><strong>Type:</strong> <span class="badge">${source.type}</span></p>
                <p><strong>Invoice #:</strong> ${source.invoiceNumber}</p>
            `;
            document.getElementById('viewModalTitle').textContent = 'Income Source Details';
            openModal('viewModal');
        }
    }

    function editIncomeSource(id) {
        const source = incomeSources.find(s => s.id === id);
        if (source) {
            document.getElementById('sourceName').value = source.sourceName;
            document.getElementById('category').value = source.category;
            document.getElementById('type').value = source.type;
            
            incomeSources = incomeSources.filter(s => s.id !== id);
            updateIncomeSourcesTable();
            
            document.getElementById('incomeSourceForm').scrollIntoView({ behavior: 'smooth' });
            showAlert('Edit the income source and click "Add Income Source" to save changes', 'success');
        }
    }

    function openDeleteModal(id, type) {
        deleteItemId = id;
        deleteItemType = type;
        openModal('deleteModal');
    }

    function confirmDelete() {
        if (deleteItemType === 'source') {
            deleteIncomeSource(deleteItemId);
        } else if (deleteItemType === 'transaction') {
            deleteTransaction(deleteItemId);
        }
        closeModal('deleteModal');
    }

    function deleteIncomeSource(id) {
        if (!id) return;

        fetch(`/admin/api/financial/income-source/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadIncomeSources();
                showAlert('Income source deleted successfully!', 'success');
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting income source: ' + error.message, 'error');
        });
    }

    function populateIncomeSourceDropdown() {
        const dropdown = document.getElementById('incomeSource');
        if (!dropdown) return;
        
        while (dropdown.options.length > 1) {
            dropdown.remove(1);
        }
        
        incomeSources.forEach(source => {
            const option = document.createElement('option');
            option.value = source.id;
            option.textContent = `${source.sourceName} (${source.category})`;
            dropdown.appendChild(option);
        });
    }

    function saveBudget() {
        const currentMonthIndex = new Date().getMonth();
        const budget = parseFloat(document.getElementById('monthlyBudget').value);
        
        if (!budget || budget <= 0) {
            showAlert('Please enter a valid budget amount', 'error');
            return;
        }
        
        fetch('/admin/api/financial/income-budget', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                month: currentMonthIndex,
                budget_amount: budget
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                budgetedIncome[currentMonthIndex] = budget;
                displayBudget(budget);
                updateCharts();
                showAlert('Budget saved successfully!', 'success');
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error saving budget: ' + error.message, 'error');
        });
    }

    function displayBudget(amount) {
        const today = new Date();
        const currentMonthName = months[today.getMonth()];
        
        document.getElementById('budgetMonth').textContent = currentMonthName;
        document.getElementById('budgetAmount').textContent = parseFloat(amount).toFixed(2);
        document.getElementById('budgetDisplayCard').style.display = 'block';
        document.getElementById('monthlyBudget').value = '';
    }

    function editBudget() {
        const currentAmount = document.getElementById('budgetAmount').textContent;
        document.getElementById('monthlyBudget').value = currentAmount;
        document.getElementById('monthlyBudget').focus();
        showAlert('Edit the budget amount and click "Save Budget" to update', 'success');
    }

    function clearBudget() {
        if (confirm('Are you sure you want to clear the budget for this month?')) {
            document.getElementById('budgetDisplayCard').style.display = 'none';
            document.getElementById('monthlyBudget').value = '';
            budgetedIncome[new Date().getMonth()] = 0;
            updateCharts();
            showAlert('Budget cleared successfully!', 'success');
        }
    }

    function saveActualIncome() {
        const incomeDate = document.getElementById('incomeDate').value;
        const incomeSourceId = document.getElementById('incomeSource').value;
        const amount = parseFloat(document.getElementById('actualMonthlyIncome').value);
        const description = document.getElementById('incomeDescription').value;
        const accountType = document.getElementById('accountType').value;
        
        if (!incomeDate || !incomeSourceId || !amount || amount <= 0 || !accountType) {
            showAlert('Please fill in all required fields', 'error');
            return;
        }
        
        const accountMap = {
            'cash_on_hand': 'cash',
            'bank_expense': 'anz_expense',
            'bank_savings': 'anz_savings',
            'm_paisa': 'cash'
        };
        
        fetch('/admin/financial/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                type: 'income',
                category: 'salary',
                description: description || 'Income Transaction',
                amount: amount,
                currency: 'USD',
                account: accountMap[accountType] || 'cash',
                transaction_date: incomeDate,
                project_id: null,
                notes: 'Added from income management page'
            })
        })
        .then(response => {
            if (response.ok) {
                document.getElementById('actualMonthlyIncome').value = '';
                document.getElementById('incomeDescription').value = '';
                document.getElementById('incomeDate').valueAsDate = new Date();
                document.getElementById('incomeSource').value = '';
                document.getElementById('accountType').value = '';
                
                loadIncomeTransactions().then(() => {
                    updateCharts();
                    showAlert('Income transaction saved successfully!', 'success');
                });
            } else {
                showAlert('Error saving income transaction', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error saving income: ' + error.message, 'error');
        });
    }

    // Pagination variables
    let allIncomeTransactions = [];
    let currentPage = 1;
    const itemsPerPage = 5;

    function loadIncomeTransactions() {
        const transactionsData = @json($incomeTransactions ?? []);
        
        allIncomeTransactions = transactionsData.map(t => ({
            id: t.id,
            transaction_date: t.transaction_date,
            amount: t.amount,
            status: t.status,
            description: t.description,
            category: t.category
        }));
        
        displayIncomeTransactions();
        return Promise.resolve();
    }

    function displayIncomeTransactions() {
        const container = document.getElementById('incomeTransactionsTable');
        const noMessage = document.getElementById('noTransactionsMessage');
        const paginationControls = document.getElementById('paginationControls');

        if (allIncomeTransactions.length === 0) {
            container.style.display = 'none';
            noMessage.style.display = 'block';
            paginationControls.style.display = 'none';
            return;
        }

        container.style.display = 'grid';
        noMessage.style.display = 'none';

        const totalPages = Math.ceil(allIncomeTransactions.length / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageTransactions = allIncomeTransactions.slice(startIndex, endIndex);

        container.innerHTML = '';

        pageTransactions.forEach(transaction => {
            const date = new Date(transaction.transaction_date).toLocaleDateString();
            const amount = parseFloat(transaction.amount).toFixed(2);
            const status = transaction.status || 'completed';
            const description = transaction.description || 'Income';
            const category = transaction.category || 'Other';

            const card = document.createElement('div');
            card.className = 'card-row';
            card.innerHTML = `
                <div class="card-content">
                    <div class="card-title">💰 ${description}</div>
                    <div class="card-meta">
                        <div class="card-meta-item">
                            <strong>Date:</strong> ${date}
                        </div>
                        <div class="card-meta-item">
                            <span class="card-badge">${category}</span>
                        </div>
                        <div class="card-meta-item">
                            <strong>Amount:</strong> $${amount}
                        </div>
                        <div class="card-meta-item">
                            <span class="card-badge">${status}</span>
                        </div>
                    </div>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn btn-small btn-view" onclick="viewTransaction(${transaction.id})">👁️ View</button>
                    <button type="button" class="btn btn-small btn-edit" onclick="editTransaction(${transaction.id})">✏️ Edit</button>
                    <button type="button" class="btn btn-small btn-delete" onclick="openDeleteModal(${transaction.id}, 'transaction')">🗑️ Delete</button>
                </div>
            `;
            container.appendChild(card);
        });

        if (totalPages > 1) {
            paginationControls.style.display = 'block';
            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            
            const prevBtn = paginationControls.querySelector('button:first-child');
            const nextBtn = paginationControls.querySelector('button:last-child');
            
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
            
            prevBtn.style.opacity = currentPage === 1 ? '0.5' : '1';
            nextBtn.style.opacity = currentPage === totalPages ? '0.5' : '1';
        } else {
            paginationControls.style.display = 'none';
        }
    }

    function nextPage() {
        const totalPages = Math.ceil(allIncomeTransactions.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayIncomeTransactions();
        }
    }

    function previousPage() {
        if (currentPage > 1) {
            currentPage--;
            displayIncomeTransactions();
        }
    }

    function viewTransaction(id) {
        const transaction = allIncomeTransactions.find(t => t.id === id);
        if (transaction) {
            const date = new Date(transaction.transaction_date).toLocaleDateString();
            const amount = parseFloat(transaction.amount).toFixed(2);
            const body = document.getElementById('viewModalBody');
            body.innerHTML = `
                <p><strong>Date:</strong> ${date}</p>
                <p><strong>Category:</strong> <span class="badge">${transaction.category}</span></p>
                <p><strong>Description:</strong> ${transaction.description || 'N/A'}</p>
                <p><strong>Amount:</strong> $${amount}</p>
                <p><strong>Status:</strong> <span class="badge">${transaction.status}</span></p>
            `;
            document.getElementById('viewModalTitle').textContent = 'Income Transaction Details';
            openModal('viewModal');
        }
    }

    function editTransaction(id) {
        showAlert('Edit functionality coming soon!', 'success');
    }

    function deleteTransaction(id) {
        if (!id) return;

        fetch(`/admin/api/financial/income-source/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadIncomeTransactions();
                showAlert('Transaction deleted successfully!', 'success');
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting transaction: ' + error.message, 'error');
        });
    }

    function updateCharts() {
        updateIncomeSummaryChart();
        updateIncomeSourcesBreakdownChart();
        updateIncomeDistributionChart();
    }

    function updateIncomeSummaryChart() {
        const ctx = document.getElementById('incomeSummaryChart');
        if (!ctx) return;
        if (ctx.chart) ctx.chart.destroy();

        const firstHalf = (monthlyIncomeData.values || actualIncome).slice(0, 6).reduce((a, b) => a + b, 0);
        const secondHalf = (monthlyIncomeData.values || actualIncome).slice(6).reduce((a, b) => a + b, 0);
        
        ctx.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['First Half', 'Second Half'],
                datasets: [{
                    data: [firstHalf || 0, secondHalf || 0],
                    backgroundColor: ['#B3D9FF', '#B3FFB3'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 } }
                    }
                }
            }
        });
    }

    function updateIncomeSourcesBreakdownChart() {
        const ctx = document.getElementById('incomeSourcesBreakdownChart');
        if (!ctx) return;
        if (ctx.chart) ctx.chart.destroy();

        const labels = incomeByCategory.map(item => {
            const cat = item.category || 'other';
            return cat.charAt(0).toUpperCase() + cat.slice(1).replace('_', ' ');
        });
        const data = incomeByCategory.map(item => item.total || 0);

        if (labels.length === 0) {
            labels.push('No Data');
            data.push(1);
        }

        ctx.chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#B3D9FF', '#B3FFB3', '#FFD9B3', '#D9B3FF', '#FFCCB3', '#FFB3D9', '#B3FFD9', '#E6D9FF'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    function updateIncomeDistributionChart() {
        const ctx = document.getElementById('incomeDistributionChart');
        if (!ctx) return;
        if (ctx.chart) ctx.chart.destroy();

        const categoryLabels = incomeByCategory.map(item => {
            const cat = item.category || 'other';
            return cat.charAt(0).toUpperCase() + cat.slice(1).replace('_', ' ');
        });
        const categoryValues = incomeByCategory.map(item => item.total || 0);

        ctx.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels.length > 0 ? categoryLabels : ['No Data'],
                datasets: [{
                    data: categoryValues.length > 0 ? categoryValues : [1],
                    backgroundColor: ['#B3D9FF', '#B3FFB3', '#FFD9B3', '#D9B3FF', '#FFCCB3', '#FFB3D9', '#B3FFD9', '#E6D9FF'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    function toggleTransactionHistory() {
        const container = document.getElementById('transactionHistoryContainer');
        const btn = document.getElementById('toggleBtnText');
        
        if (container.style.display === 'none') {
            container.style.display = 'block';
            btn.textContent = 'Hide Transactions';
        } else {
            container.style.display = 'none';
            btn.textContent = 'Show Transactions';
        }
    }

    function toggleIncomeSourceForm() {
        const container = document.getElementById('incomeSourceFormContainer');
        const btn = document.getElementById('toggleSourceBtnText');
        
        if (container.style.display === 'none') {
            container.style.display = 'block';
            btn.textContent = 'Hide Form';
        } else {
            container.style.display = 'none';
            btn.textContent = 'Show Form';
        }
    }

    window.addEventListener('DOMContentLoaded', function() {
        initializeCurrentMonth();
        loadIncomeSources();
        loadIncomeTransactions();
        updateCharts();
    });
</script>
@endsection
