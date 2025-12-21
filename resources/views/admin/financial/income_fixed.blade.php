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

    .income-container {
        background: transparent;
        min-height: auto;
        padding: 0;
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
    }

    .card-row:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        border-color: #B3D9FF;
        transform: translateY(-2px);
    }

    .card-content {
        display: grid;
        gap: 12px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0F172A;
    }

    .card-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #555;
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

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    table thead tr {
        background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%);
        border-bottom: 2px solid #0F172A;
    }

    table th {
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: #0F172A;
        border-right: 1px solid rgba(0,0,0,0.1);
    }

    table td {
        padding: 15px;
        border-right: 1px solid #e8e8e8;
        color: #333;
    }

    table tbody tr:nth-child(even) {
        background: #f8f9fa;
    }

    table tbody tr:hover {
        background: #f0f7ff;
    }
</style>

<div class="toast-container" id="toastContainer"></div>

<div class="income-container">
    <a href="/admin/financial" class="back-button">← Back to Dashboard</a>

    <div class="page-header">
        <h1>💰 Income Management</h1>
        <p>Track and manage all your income sources and transactions</p>
    </div>

    <!-- Account Management Section -->
    <div class="form-section" style="background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(179, 255, 179, 0.1) 100%); border-left: 5px solid #B3D9FF;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px solid #B3D9FF;">
            <h2 class="section-title" style="color: #0F172A; font-size: 20px; border-bottom: none; margin: 0;">🏦 Account Management</h2>
            <button type="button" class="btn btn-primary" onclick="toggleAccountForm()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 600; padding: 8px 16px; font-size: 12px;">
                <span id="toggleAccountBtnText">Add Account</span>
            </button>
        </div>
        <p style="color: #555; margin-bottom: 20px; font-size: 14px; font-weight: 500;">Manage your income allocation accounts</p>

        <div id="accountFormContainer" style="display: none;">
            <form id="accountForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="accountName" style="color: #0F172A; font-weight: 700;">Account Name *</label>
                        <input type="text" id="accountName" placeholder="e.g., Cash On Hand, Bank Savings" required>
                    </div>
                    <div class="form-group">
                        <label for="accountType" style="color: #0F172A; font-weight: 700;">Account Type *</label>
                        <select id="accountType" required>
                            <option value="">Select Type</option>
                            <option value="cash">💵 Cash</option>
                            <option value="bank_savings">🏦 Bank Savings</option>
                            <option value="bank_expense">🏦 Bank Expense</option>
                            <option value="digital_wallet">📱 Digital Wallet</option>
                            <option value="investment">📈 Investment</option>
                            <option value="other">📌 Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="accountBalance" style="color: #0F172A; font-weight: 700;">Initial Balance *</label>
                        <input type="number" id="accountBalance" placeholder="$0.00" min="0" step="0.01" required>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addAccount()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 700; box-shadow: 0 4px 12px rgba(179, 217, 255, 0.3);">+ Add Account</button>
            </form>
        </div>

        <!-- Accounts Table Section -->
        <div style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px solid #B3D9FF;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">📋 Added Accounts</h3>
                <button type="button" class="btn btn-primary" onclick="toggleAccountsTable()" style="background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); font-weight: 600; padding: 8px 16px; font-size: 12px;">
                    <span id="toggleAccountsBtnText">Show Accounts</span>
                </button>
            </div>
            
            <div id="accountsTableContainer" style="display: none;">
                <div style="overflow-x: auto; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <table id="accountsListTable" style="display: none;">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Type</th>
                                <th>Balance</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="accountsListTableBody"></tbody>
                    </table>
                </div>

                <div id="noAccountsMessage" style="text-align: center; padding: 40px 30px; color: #999; font-size: 14px; background: white; border-radius: 8px; border: 2px dashed #B3D9FF;">
                    <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                    No accounts added yet. Add one above to get started!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let allAccounts = [];

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
        `;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    function toggleAccountForm() {
        const container = document.getElementById('accountFormContainer');
        const btn = document.getElementById('toggleAccountBtnText');
        
        if (container.style.display === 'none') {
            container.style.display = 'block';
            btn.textContent = 'Hide Form';
        } else {
            container.style.display = 'none';
            btn.textContent = 'Add Account';
        }
    }

    function addAccount() {
        const accountName = document.getElementById('accountName').value;
        const accountType = document.getElementById('accountType').value;
        const accountBalance = parseFloat(document.getElementById('accountBalance').value);

        if (!accountName || !accountType || !accountBalance) {
            showAlert('Please fill in all fields', 'error');
            return;
        }

        fetch('/admin/api/financial/account', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                account_name: accountName,
                account_type: accountType,
                initial_balance: accountBalance
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('accountName').value = '';
                document.getElementById('accountType').value = '';
                document.getElementById('accountBalance').value = '';
                
                return loadAccounts();
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .then(() => {
            updateAccountsTable();
            showAlert('Account created and saved to database successfully!', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error saving account: ' + error.message, 'error');
        });
    }

    function loadAccounts() {
        return fetch('/admin/api/financial/accounts', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                allAccounts = data.data.map(account => ({
                    id: account.id,
                    name: account.name,
                    type: account.type,
                    balance: account.balance,
                    createdAt: account.created_at
                }));
                console.log('Loaded accounts from database:', allAccounts);
            }
            return data;
        })
        .catch(error => {
            console.error('Error loading accounts:', error);
            throw error;
        });
    }

    function updateAccountsTable() {
        const table = document.getElementById('accountsListTable');
        const tbody = document.getElementById('accountsListTableBody');
        const noMessage = document.getElementById('noAccountsMessage');

        if (allAccounts.length === 0) {
            table.style.display = 'none';
            noMessage.style.display = 'block';
            return;
        }

        table.style.display = 'table';
        noMessage.style.display = 'none';
        tbody.innerHTML = '';

        allAccounts.forEach((account, index) => {
            const typeEmoji = {
                'cash': '💵',
                'bank_savings': '🏦',
                'bank_expense': '🏦',
                'digital_wallet': '📱',
                'investment': '📈',
                'other': '📌'
            };

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${typeEmoji[account.type] || '💳'} ${account.name}</td>
                <td><span style="display: inline-block; padding: 4px 10px; border-radius: 12px; background: linear-gradient(135deg, #B3D9FF 0%, #a5d4ff 100%); color: #0F172A; font-weight: 600; font-size: 11px;">${account.type.replace('_', ' ')}</span></td>
                <td style="color: #16A34A; font-weight: 700;">$${parseFloat(account.balance).toFixed(2)}</td>
                <td>${account.createdAt}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-small btn-edit" onclick="editAccount(${account.id})" style="padding: 6px 10px; font-size: 11px; margin-right: 4px;">✏️</button>
                    <button type="button" class="btn btn-small btn-delete" onclick="deleteAccount(${account.id})" style="padding: 6px 10px; font-size: 11px;">🗑️</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function toggleAccountsTable() {
        const container = document.getElementById('accountsTableContainer');
        const btn = document.getElementById('toggleAccountsBtnText');
        
        if (container.style.display === 'none') {
            container.style.display = 'block';
            btn.textContent = 'Hide Accounts';
        } else {
            container.style.display = 'none';
            btn.textContent = 'Show Accounts';
        }
    }

    function editAccount(id) {
        const account = allAccounts.find(a => a.id === id);
        if (account) {
            document.getElementById('accountName').value = account.name;
            document.getElementById('accountType').value = account.type;
            document.getElementById('accountBalance').value = account.balance;
            document.getElementById('accountFormContainer').style.display = 'block';
            document.getElementById('accountForm').scrollIntoView({ behavior: 'smooth' });
            showAlert('Edit the account details and click "Add Account" to save changes', 'success');
        }
    }

    function deleteAccount(id) {
        if (confirm('Are you sure you want to delete this account?')) {
            fetch(`/admin/api/financial/account/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAccounts().then(() => {
                        updateAccountsTable();
                        showAlert('Account deleted successfully!', 'success');
                    });
                } else {
                    showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting account: ' + error.message, 'error');
            });
        }
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing...');
        loadAccounts().then(() => {
            updateAccountsTable();
            console.log('Accounts loaded:', allAccounts);
        }).catch(error => {
            console.error('Error loading accounts on page load:', error);
        });
    });
</script>
@endsection
