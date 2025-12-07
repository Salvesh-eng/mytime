@extends('layouts.app')

@section('page-title', 'Expense Management')

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

    .expense-container {
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
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
        border-bottom: 2px solid var(--soft-pink);
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
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--soft-pink);
        box-shadow: 0 0 0 3px rgba(255, 179, 217, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 13px;
    }

    .table th {
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
        padding: 12px;
        text-align: left;
        font-weight: 700;
        color: #333;
        border-bottom: 2px solid #ddd;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .table tbody tr:hover {
        background: #f9f9f9;
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
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
        color: #333;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-small {
        padding: 6px 10px;
        font-size: 11px;
        margin-right: 4px;
    }

    .btn-view {
        background: var(--soft-pink);
        color: #333;
    }

    .btn-edit {
        background: var(--soft-orange);
        color: #333;
    }

    .btn-delete {
        background: var(--soft-blue);
        color: #333;
    }

    .btn-small:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .summary-card {
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
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

    /* Toast Notification Styles - Side Position */
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

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: none;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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

    .invoice-number {
        font-family: monospace;
        font-size: 11px;
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        color: #666;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
        padding: 16px;
        background: linear-gradient(135deg, rgba(255, 179, 217, 0.1) 0%, rgba(179, 217, 255, 0.1) 100%);
        border: 1px solid var(--soft-pink);
        border-radius: 8px;
    }

    .pagination-btn {
        padding: 8px 14px;
        border: 1px solid var(--soft-pink);
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: var(--soft-pink);
        white-space: nowrap;
    }

    .pagination-btn:hover:not(:disabled) {
        background: var(--soft-pink);
        border-color: var(--soft-pink);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(255, 179, 217, 0.3);
    }

    .pagination-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f5f5f5;
    }

    .pagination-info {
        font-size: 13px;
        color: #333;
        font-weight: 600;
        padding: 0 12px;
        border-left: 2px solid var(--soft-pink);
        border-right: 2px solid var(--soft-pink);
        min-width: 140px;
        text-align: center;
    }

    .table-compact {
        font-size: 12px;
    }

    .table-compact th {
        padding: 8px;
        font-size: 11px;
    }

    .table-compact td {
        padding: 8px;
        font-size: 11px;
    }

    @media (max-width: 1200px) {
        .charts-grid {
            grid-template-columns: repeat(2, 1fr);
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
    }
</style>

<div class="toast-container" id="toastContainer"></div>

<div class="expense-container">
    <a href="/admin/financial" class="back-button">← Back to Dashboard</a>

    <div class="page-header">
        <h1>💸 Expense Management</h1>
        <p>Track and manage all your expenses</p>
    </div>

    <div id="alertContainer"></div>

    <!-- Add Expense Section -->
    <div class="form-section">
        <h2 class="section-title">1. Record Your Expenses</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Add a new expense transaction</p>

        <form id="expenseForm">
            <!-- Row 1: Expense Name -->
            <div class="form-row">
                <div class="form-group">
                    <label for="expenseName">Expense Name *</label>
                    <input type="text" id="expenseName" placeholder="e.g., Office Supplies, Groceries, etc." required>
                </div>
            </div>

            <!-- Row 2: Category and Type -->
            <div class="form-row">
                <div class="form-group">
                    <label for="expenseCategory">Category *</label>
                    <select id="expenseCategory" required>
                        <option value="">Select Category</option>
                        <option value="groceries">Groceries & Food</option>
                        <option value="shopping">Shopping & Retail</option>
                        <option value="bills">Bills & Utilities</option>
                        <option value="rent">Rent/Lease</option>
                        <option value="transportation">Transportation & Fuel</option>
                        <option value="dining">Dining & Restaurants</option>
                        <option value="entertainment">Entertainment & Hobbies</option>
                        <option value="healthcare">Healthcare & Medical</option>
                        <option value="education">Education & Training</option>
                        <option value="insurance">Insurance</option>
                        <option value="subscriptions">Subscriptions & Memberships</option>
                        <option value="phone_internet">Phone & Internet</option>
                        <option value="utilities">Utilities & Services</option>
                        <option value="maintenance">Maintenance & Repairs</option>
                        <option value="office_supplies">Office Supplies</option>
                        <option value="software">Software & Licenses</option>
                        <option value="equipment">Equipment & Hardware</option>
                        <option value="travel">Travel & Accommodation</option>
                        <option value="professional_services">Professional Services</option>
                        <option value="marketing">Marketing & Advertising</option>
                        <option value="personal_care">Personal Care & Beauty</option>
                        <option value="gifts">Gifts & Donations</option>
                        <option value="other">Other Expenses</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="expenseType">Type *</label>
                    <select id="expenseType" required>
                        <option value="">Select Type</option>
                        <option value="fixed">Fixed</option>
                        <option value="variable">Variable</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Amount and Date -->
            <div class="form-row">
                <div class="form-group">
                    <label for="expenseAmount">Amount *</label>
                    <input type="number" id="expenseAmount" placeholder="$0.00" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="expenseDate">Date *</label>
                    <input type="date" id="expenseDate" required>
                    <small style="color: #999; margin-top: 4px; display: block;">Format: YYYY-MM-DD</small>
                </div>
            </div>

            <!-- Row 4: Notes -->
            <div class="form-row">
                <div class="form-group">
                    <label for="expenseNotes">Notes (Optional)</label>
                    <input type="text" id="expenseNotes" placeholder="Add any additional notes about this expense">
                </div>
            </div>

            <button type="button" class="btn btn-primary" onclick="addExpense()">+ Add Expense</button>
        </form>

        <table class="table table-compact" id="expensesTable" style="display: none;">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Expense Name</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="expensesBody">
            </tbody>
        </table>
        <div id="paginationContainer" style="display: none;"></div>
    </div>

    <!-- Monthly Budget Section -->
    <div class="form-section">
        <h2 class="section-title">2. Monthly Expense Budget</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Set your expense budget</p>

        <form id="budgetForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="currentMonth">Current Month</label>
                    <input type="text" id="currentMonth" readonly style="background: #f5f5f5; cursor: not-allowed;">
                </div>
                <div class="form-group">
                    <label for="monthlyBudget">Monthly Expense Budget *</label>
                    <input type="number" id="monthlyBudget" placeholder="$0.00" min="0" step="0.01" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="saveBudget()">💾 Save Budget</button>
        </form>
    </div>

    <!-- Summary Section -->
    <div class="form-section">
        <h2 class="section-title">3. Summary</h2>
        
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-label">Total Monthly Expenses</div>
                <div class="summary-card-value" id="totalMonthlyExpenses">$0.00</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Total Annual Expenses</div>
                <div class="summary-card-value" id="totalAnnualExpenses">$0.00</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Average Monthly Expense</div>
                <div class="summary-card-value" id="averageMonthlyExpense">$0.00</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Total Expenses</div>
                <div class="summary-card-value" id="totalExpenseCount">0</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="form-section">
        <h2 class="section-title">4. Analytics</h2>
        
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-title">Expense Distribution by Category</div>
                <div class="chart-wrapper">
                    <canvas id="expenseDistributionChart" style="max-width: 100%; max-height: 250px;"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Expense by Type</div>
                <div class="chart-wrapper">
                    <canvas id="expenseByTypeChart" style="max-width: 100%; max-height: 250px;"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Monthly Expense Trend</div>
                <div class="chart-wrapper">
                    <canvas id="monthlyTrendChart" style="max-width: 100%; max-height: 250px;"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-title">Top Expense Categories</div>
                <div class="chart-wrapper">
                    <canvas id="topCategoriesChart" style="max-width: 100%; max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let expenses = [];
    let invoiceCounter = 2000;

    function generateInvoiceNumber() {
        invoiceCounter++;
        return `EXP-${new Date().getFullYear()}-${String(invoiceCounter).slice(-5)}`;
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
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function initializeCurrentMonth() {
        const today = new Date();
        const currentMonthIndex = today.getMonth();
        const currentMonthName = months[currentMonthIndex];
        
        document.getElementById('currentMonth').value = currentMonthName;
        document.getElementById('expenseDate').valueAsDate = new Date();
    }

    function addExpense() {
        const name = document.getElementById('expenseName').value;
        const category = document.getElementById('expenseCategory').value;
        const type = document.getElementById('expenseType').value;
        const amount = parseFloat(document.getElementById('expenseAmount').value);
        const date = document.getElementById('expenseDate').value;
        const notes = document.getElementById('expenseNotes').value;

        if (!name || !category || !type || !amount || !date) {
            showAlert('Please fill in all required fields', 'error');
            return;
        }

        const invoiceNumber = generateInvoiceNumber();

        fetch('/admin/api/financial/expense', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                expense_name: name,
                category: category,
                type: type,
                amount: amount,
                date: date,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const expense = { name, category, type, amount, date, notes, id: data.data.id, invoiceNumber };
                expenses.push(expense);

                document.getElementById('expenseName').value = '';
                document.getElementById('expenseCategory').value = '';
                document.getElementById('expenseType').value = '';
                document.getElementById('expenseAmount').value = '';
                document.getElementById('expenseNotes').value = '';

                updateExpensesTable();
                updateSummary();
                updateCharts();
                showAlert('Expense saved successfully!', 'success');
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error saving expense: ' + error.message, 'error');
        });
    }

    let currentPage = 1;
    const itemsPerPage = 5;

    function updateExpensesTable() {
        const table = document.getElementById('expensesTable');
        const tbody = document.getElementById('expensesBody');
        const paginationContainer = document.getElementById('paginationContainer');

        if (expenses.length === 0) {
            table.style.display = 'none';
            paginationContainer.style.display = 'none';
            return;
        }

        table.style.display = 'table';
        tbody.innerHTML = '';

        const totalPages = Math.ceil(expenses.length / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedExpenses = expenses.slice(startIndex, endIndex);

        paginatedExpenses.forEach(expense => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><span class="invoice-number">${expense.invoiceNumber}</span></td>
                <td>${expense.date}</td>
                <td>${expense.name}</td>
                <td><span style="background: var(--soft-pink); padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">${expense.category}</span></td>
                <td><span style="background: var(--soft-orange); padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 600;">${expense.type}</span></td>
                <td>$${expense.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>
                    <button type="button" class="btn btn-small btn-view" onclick="viewExpense(${expense.id})">View</button>
                    <button type="button" class="btn btn-small btn-edit" onclick="editExpense(${expense.id})">Edit</button>
                    <button type="button" class="btn btn-small btn-delete" onclick="deleteExpense(${expense.id})">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });

        if (totalPages > 1) {
            paginationContainer.style.display = 'flex';
            paginationContainer.innerHTML = '';

            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.textContent = '← Previous';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateExpensesTable();
                }
            };
            paginationContainer.appendChild(prevBtn);

            const pageInfo = document.createElement('span');
            pageInfo.className = 'pagination-info';
            pageInfo.textContent = `Page ${currentPage} of ${totalPages} (${expenses.length} total)`;
            paginationContainer.appendChild(pageInfo);

            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.textContent = 'Next →';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateExpensesTable();
                }
            };
            paginationContainer.appendChild(nextBtn);
        } else {
            paginationContainer.style.display = 'none';
        }
    }

    function viewExpense(id) {
        const expense = expenses.find(e => e.id === id);
        if (expense) {
            alert(`Expense: ${expense.name}
Category: ${expense.category}
Type: ${expense.type}
Amount: $${expense.amount}
Date: ${expense.date}
Invoice: ${expense.invoiceNumber}`);
        }
    }

    function editExpense(id) {
        const expense = expenses.find(e => e.id === id);
        if (expense) {
            document.getElementById('expenseName').value = expense.name;
            document.getElementById('expenseCategory').value = expense.category;
            document.getElementById('expenseType').value = expense.type;
            document.getElementById('expenseAmount').value = expense.amount;
            document.getElementById('expenseDate').value = expense.date;
            document.getElementById('expenseNotes').value = expense.notes;
            deleteExpense(id);
        }
    }

    function deleteExpense(id) {
        if (!confirm('Are you sure you want to delete this expense?')) return;

        fetch(`/admin/api/financial/expense/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                expenses = expenses.filter(e => e.id !== id);
                currentPage = 1;
                updateExpensesTable();
                updateSummary();
                updateCharts();
                showAlert('Expense deleted successfully!', 'success');
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting expense: ' + error.message, 'error');
        });
    }

    function updateSummary() {
        let totalMonthly = 0;
        let totalAnnual = 0;
        const currentMonthIndex = new Date().getMonth();

        expenses.forEach(expense => {
            const amount = parseFloat(expense.amount) || 0;
            totalAnnual += amount;
            
            const expenseDate = new Date(expense.date);
            if (expenseDate.getMonth() === currentMonthIndex) {
                totalMonthly += amount;
            }
        });

        const averageMonthly = expenses.length > 0 ? totalAnnual / 12 : 0;

        document.getElementById('totalMonthlyExpenses').textContent = '$' + totalMonthly.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('totalAnnualExpenses').textContent = '$' + totalAnnual.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('averageMonthlyExpense').textContent = '$' + averageMonthly.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('totalExpenseCount').textContent = expenses.length;
    }

    function updateCharts() {
        updateExpenseDistributionChart();
        updateExpenseByTypeChart();
        updateMonthlyTrendChart();
        updateTopCategoriesChart();
    }

    function updateExpenseDistributionChart() {
        const ctx = document.getElementById('expenseDistributionChart');
        if (ctx.chart) ctx.chart.destroy();

        const categoryTotals = {};
        expenses.forEach(expense => {
            const amount = parseFloat(expense.amount) || 0;
            categoryTotals[expense.category] = (categoryTotals[expense.category] || 0) + amount;
        });

        ctx.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(categoryTotals),
                datasets: [{
                    data: Object.values(categoryTotals),
                    backgroundColor: ['#FFB3D9', '#B3D9FF', '#B3FFB3', '#FFD9B3', '#D9B3FF', '#FFCCB3', '#FFB3D9', '#B3FFD9'],
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

    function updateExpenseByTypeChart() {
        const ctx = document.getElementById('expenseByTypeChart');
        if (ctx.chart) ctx.chart.destroy();

        const typeTotals = {};
        expenses.forEach(expense => {
            const amount = parseFloat(expense.amount) || 0;
            typeTotals[expense.type] = (typeTotals[expense.type] || 0) + amount;
        });

        ctx.chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(typeTotals),
                datasets: [{
                    data: Object.values(typeTotals),
                    backgroundColor: ['#FFD9B3', '#D9B3FF'],
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

    function updateMonthlyTrendChart() {
        const ctx = document.getElementById('monthlyTrendChart');
        if (ctx.chart) ctx.chart.destroy();

        const monthlyExpenses = new Array(12).fill(0);
        expenses.forEach(expense => {
            const date = new Date(expense.date);
            const monthIndex = date.getMonth();
            const amount = parseFloat(expense.amount) || 0;
            monthlyExpenses[monthIndex] += amount;
        });

        ctx.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Expenses',
                    data: monthlyExpenses,
                    borderColor: '#FFB3D9',
                    backgroundColor: 'rgba(255, 179, 217, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { font: { size: 10 } } }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function updateTopCategoriesChart() {
        const ctx = document.getElementById('topCategoriesChart');
        if (ctx.chart) ctx.chart.destroy();

        const categoryTotals = {};
        expenses.forEach(expense => {
            const amount = parseFloat(expense.amount) || 0;
            categoryTotals[expense.category] = (categoryTotals[expense.category] || 0) + amount;
        });

        const sorted = Object.entries(categoryTotals)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 5);

        ctx.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sorted.map(item => item[0]),
                datasets: [{
                    label: 'Amount',
                    data: sorted.map(item => item[1]),
                    backgroundColor: ['#FFB3D9', '#B3D9FF', '#B3FFB3', '#FFD9B3', '#D9B3FF'],
                    borderColor: '#333',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    }

    function saveBudget() {
        const budget = parseFloat(document.getElementById('monthlyBudget').value);
        
        if (!budget || budget <= 0) {
            showAlert('Please enter a valid budget amount', 'error');
            return;
        }
        
        const currentMonthIndex = new Date().getMonth();
        
        fetch('/admin/api/financial/expense-budget', {
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
                document.getElementById('monthlyBudget').value = '';
                updateSummary();
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

    function loadExpenses() {
        fetch('/admin/api/financial/expenses', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                expenses = data.data.map(item => ({
                    id: item.id,
                    name: item.description,
                    category: item.category,
                    type: item.notes ? JSON.parse(item.notes).expense_type : 'variable',
                    amount: item.amount,
                    date: item.transaction_date,
                    notes: item.notes,
                    invoiceNumber: `EXP-${item.id}`
                }));
                updateExpensesTable();
                updateSummary();
                updateCharts();
            }
        })
        .catch(error => console.error('Error loading expenses:', error));
    }

    window.addEventListener('DOMContentLoaded', function() {
        initializeCurrentMonth();
        loadExpenses();
    });
</script>
@endsection
