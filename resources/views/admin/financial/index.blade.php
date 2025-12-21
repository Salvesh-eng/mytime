@extends('layouts.app')

@section('page-title', 'Financial Management Dashboard')

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

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #F5F5F5;
    }

    .financial-dashboard {
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
        max-width: 100%;
        width: 100%;
    }

    .dashboard-title {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Budget Summary Section */
    .budget-summary {
        background: var(--light-green);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .budget-summary-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    .budget-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .budget-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        border-top: 4px solid #4CAF50;
    }

    .budget-card.income {
        border-top-color: #5DADE2;
        background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    }

    .budget-card.expense {
        border-top-color: #FF6B9D;
        background: linear-gradient(135deg, #FCE4EC 0%, #F8BBD0 100%);
    }

    .budget-card.savings {
        border-top-color: #FFA500;
        background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
    }

    .budget-card-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .budget-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .budget-card-sublabel {
        font-size: 11px;
        color: #999;
    }

    /* Main Grid Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .dashboard-grid.three-col {
        grid-template-columns: repeat(3, 1fr);
    }

    .dashboard-grid.full {
        grid-template-columns: 1fr;
    }

    .card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        text-align: center;
        text-transform: uppercase;
    }

    .card-header {
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
    }

    .card-header.pink {
        background: linear-gradient(135deg, #FCE4EC 0%, #F8BBD0 100%);
    }

    .card-header.blue {
        background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    }

    .card-header.orange {
        background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
    }

    /* Charts */
    .chart-container {
        position: relative;
        height: 250px;
        margin-bottom: 15px;
    }

    .pie-chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .data-table th {
        background: #F5F5F5;
        padding: 10px;
        text-align: left;
        font-weight: 600;
        color: #666;
        border-bottom: 2px solid #E0E0E0;
    }

    .data-table td {
        padding: 10px;
        border-bottom: 1px solid #E0E0E0;
        color: #333;
    }

    .data-table tbody tr:hover {
        background: #F9F9F9;
    }

    .percentage {
        color: #4CAF50;
        font-weight: 600;
    }

    .amount {
        font-weight: 600;
        color: #333;
    }

    /* Savings Funds Summary */
    .savings-summary {
        background: linear-gradient(135deg, #F8BBD0 0%, #FCE4EC 100%);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .savings-summary-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        text-align: center;
    }

    .savings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .savings-table th {
        background: rgba(255,255,255,0.7);
        padding: 12px;
        text-align: center;
        font-weight: 700;
        color: #333;
        border-bottom: 2px solid #FF6B9D;
    }

    .savings-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.5);
        color: #333;
    }

    .savings-table tbody tr:hover {
        background: rgba(255,255,255,0.5);
    }

    /* Progress Bars */
    .progress-bar {
        width: 100%;
        height: 8px;
        background: #E0E0E0;
        border-radius: 4px;
        overflow: hidden;
        margin: 8px 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50 0%, #66BB6A 100%);
        border-radius: 4px;
    }

    .progress-fill.orange {
        background: linear-gradient(90deg, #FFA500 0%, #FFB74D 100%);
    }

    .progress-fill.pink {
        background: linear-gradient(90deg, #FF6B9D 0%, #FF8FB3 100%);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-grid,
        .dashboard-grid.three-col {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .budget-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .dashboard-grid,
        .dashboard-grid.three-col {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .budget-cards-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .financial-dashboard {
            padding: 15px;
        }
        
        .dashboard-title {
            font-size: 24px;
            padding: 15px;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-grid,
        .dashboard-grid.three-col {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .budget-cards-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .financial-dashboard {
            padding: 12px;
        }
        
        .dashboard-title {
            font-size: 20px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .budget-summary {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .budget-summary-title {
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .budget-card {
            padding: 15px;
        }
        
        .budget-card-label {
            font-size: 11px;
        }
        
        .budget-card-value {
            font-size: 20px;
        }
        
        .card {
            padding: 15px;
        }
        
        .card-title {
            font-size: 13px;
            margin-bottom: 12px;
        }
        
        .chart-container {
            height: 200px;
        }
        
        .data-table {
            font-size: 11px;
        }
        
        .data-table th,
        .data-table td {
            padding: 8px;
        }
        
        .savings-table th,
        .savings-table td {
            padding: 10px;
            font-size: 11px;
        }
        
        .savings-summary-title {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .dashboard-grid,
        .dashboard-grid.three-col {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .budget-cards-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .financial-dashboard {
            padding: 10px;
        }
        
        .dashboard-title {
            font-size: 18px;
            padding: 10px;
            margin-bottom: 12px;
        }
        
        .budget-summary {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .budget-card {
            padding: 12px;
        }
        
        .budget-card-label {
            font-size: 10px;
        }
        
        .budget-card-value {
            font-size: 18px;
        }
        
        .budget-card-button {
            padding: 8px 12px;
            font-size: 11px;
            margin-top: 10px;
        }
        
        .card {
            padding: 12px;
        }
        
        .card-title {
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .chart-container {
            height: 180px;
        }
        
        .pie-chart-container {
            height: 150px;
        }
        
        .data-table {
            font-size: 10px;
        }
        
        .data-table th,
        .data-table td {
            padding: 6px;
        }
        
        .savings-table {
            font-size: 10px;
        }
        
        .savings-table th,
        .savings-table td {
            padding: 8px;
        }
    }

    .text-center {
        text-align: center;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .budget-card-button {
        display: inline-block;
        margin-top: 12px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #FFB3D9 0%, #B3D9FF 100%);
        color: #333;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .budget-card-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .budget-card-button.income-btn {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
    }

    .budget-card-button.expense-btn {
        background: linear-gradient(135deg, #FFB3D9 0%, #FFD9B3 100%);
    }

    .budget-card-button.savings-btn {
        background: linear-gradient(135deg, #FFD9B3 0%, #D9B3FF 100%);
    }

    .budget-card-button.donation-btn {
        background: linear-gradient(135deg, #D9B3FF 0%, #B3FFD9 100%);
    }
</style>

<div class="financial-dashboard">
    <!-- Budget Summary Section -->
    <div class="budget-summary">
        <div class="budget-summary-title">BUDGET SUMMARY - Real-Time Data</div>
        <div class="budget-cards-grid">
            <div class="budget-card income">
                <div class="budget-card-label">TOTAL INCOME</div>
                <div class="budget-card-value">${{ number_format($totalIncome, 0) }}</div>
                <a href="/admin/financial/income" class="budget-card-button income-btn">+ Add Income</a>
            </div>
            <div class="budget-card expense">
                <div class="budget-card-label">TOTAL EXPENSES</div>
                <div class="budget-card-value">${{ number_format($totalExpenses, 0) }}</div>
                <a href="/admin/financial/expense" class="budget-card-button expense-btn">+ Add Expense</a>
            </div>
            <div class="budget-card savings">
                <div class="budget-card-label">BEST SAVING MONTH</div>
                <div class="budget-card-value">{{ $bestSavingMonth }}</div>
                <a href="/admin/financial/savings" class="budget-card-button savings-btn">+ Add Savings</a>
            </div>
            <div class="budget-card income">
                <div class="budget-card-label">NET PROFIT</div>
                <div class="budget-card-value" style="{{ $netProfit >= 0 ? 'color: #16A34A;' : 'color: #DC2626;' }}">${{ number_format($netProfit, 0) }}</div>
                <a href="/admin/financial/income" class="budget-card-button income-btn">View Details</a>
            </div>
            <div class="budget-card savings">
                <div class="budget-card-label">WALLET BALANCE</div>
                <div class="budget-card-value">${{ number_format($totalInWallet, 0) }}</div>
                <a href="/admin/financial/donation" class="budget-card-button donation-btn">+ Donate</a>
            </div>
            <div class="budget-card expense">
                <div class="budget-card-label">BIGGEST EXPENSE</div>
                <div class="budget-card-value">{{ $biggestExpenseCategory }}</div>
                <a href="/admin/financial/expense" class="budget-card-button expense-btn">View Details</a>
            </div>
        </div>
    </div>

    <!-- Main Charts Grid - 3 Charts Per Row -->
    <div class="dashboard-grid three-col">
        <!-- Bills Distribution -->
        <div class="card">
            <div class="card-title">BILLS/SUBSCRIPTIONS DISTRIBUTION</div>
            <div class="card-header pink">
                <div style="font-size: 12px; color: #666;">FROM DATABASE</div>
            </div>
            <div class="pie-chart-container">
                <canvas id="billsChart" style="max-width: 200px; max-height: 200px;"></canvas>
            </div>
        </div>

        <!-- Annual Income vs Expenses -->
        <div class="card">
            <div class="card-title">MONTHLY INCOME vs EXPENSES</div>
            <div class="card-header blue">
                <div style="font-size: 12px; color: #666;">{{ now()->year }} DATA</div>
            </div>
            <div class="chart-container">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Where Does My Money Go -->
        <div class="card">
            <div class="card-title">WHERE DOES MY MONEY GO?</div>
            <div class="card-header orange">
                <div style="font-size: 12px; color: #666;">EXPENSE CATEGORIES</div>
            </div>
            <div class="pie-chart-container">
                <canvas id="moneyGoChart" style="max-width: 200px; max-height: 200px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Second Row - 3 Charts -->
    <div class="dashboard-grid three-col">
        <!-- My Real Budget Breakdown -->
        <div class="card">
            <div class="card-title">SAVINGS vs EXPENSES RATIO</div>
            <div class="card-header pink">
                <div style="font-size: 12px; color: #666;">REAL BREAKDOWN</div>
            </div>
            <div class="pie-chart-container">
                <canvas id="budgetBreakdownChart" style="max-width: 200px; max-height: 200px;"></canvas>
            </div>
            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-size: 12px; font-weight: 600;">Savings vs Expenses</span>
                    <span style="font-size: 12px; font-weight: 600;">{{ $savingsVsExpenses['savings'] }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $savingsVsExpenses['savings'] }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Expense Category Table -->
        <div class="card">
            <div class="card-title">EXPENSE CATEGORY BREAKDOWN</div>
            <div class="card-header orange">
                <div style="font-size: 12px; color: #666;">TOP EXPENSES</div>
            </div>
            <table class="data-table" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalExpenseAmount = collect($expenseByCategory)->sum('total');
                    @endphp
                    @forelse($expenseByCategory as $expense)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $expense['category'])) }}</td>
                            <td class="amount">${{ number_format($expense['total'], 0) }}</td>
                            <td class="percentage">{{ $totalExpenseAmount > 0 ? round(($expense['total'] / $totalExpenseAmount) * 100) : 0 }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #999;">No expense data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Income vs Savings Trend -->
        <div class="card">
            <div class="card-title">INCOME vs SAVINGS TREND</div>
            <div class="card-header blue">
                <div style="font-size: 12px; color: #666;">MONTHLY COMPARISON</div>
            </div>
            <div class="chart-container">
                <canvas id="incomeSavingsTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Household Expenses Overview - Three Column Layout -->
    <div class="dashboard-grid three-col">
        <!-- Household Expenses Overview -->
        <div class="card">
            <div class="card-title">EXPENSE BREAKDOWN OVERVIEW</div>
            <div class="card-header blue">
                <div style="font-size: 12px; color: #666;">DEBTS, SAVINGS, SUBSCRIPTIONS</div>
            </div>
            <div class="chart-container">
                <canvas id="householdExpensesChart"></canvas>
            </div>
        </div>

        <!-- Monthly Savings/Debts -->
        <div class="card">
            <div class="card-title">MONTHLY SAVINGS / DEBTS</div>
            <div class="card-header orange">
                <div style="font-size: 12px; color: #666;">SAVINGS vs DEBTS vs THRESHOLD</div>
            </div>
            <div class="chart-container">
                <canvas id="monthlySavingsChart"></canvas>
            </div>
        </div>

        <!-- Savings Over The Year -->
        <div class="card">
            <div class="card-title">SAVINGS OVER THE YEAR</div>
            <div class="card-header pink">
                <div style="font-size: 12px; color: #666;">MONTHLY SAVINGS vs CUMULATIVE</div>
            </div>
            <div class="chart-container">
                <canvas id="savingsOverYearChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Needs -->
    <div class="card mb-20">
        <div class="card-title">MONTHLY NEEDS</div>
        <div class="card-header orange">
            <div style="font-size: 12px; color: #666;">NEEDS vs THRESHOLD</div>
        </div>
        <div class="chart-container">
            <canvas id="monthlyNeedsChart"></canvas>
        </div>
    </div>

    <!-- Savings Funds Summary -->
    @if($savingsFunds->count() > 0)
    <div class="savings-summary">
        <div class="savings-summary-title">SAVINGS FUNDS SUMMARY</div>
        <table class="savings-table">
            <thead>
                <tr>
                    <th>FUND NAME</th>
                    <th>GOAL</th>
                    <th>SAVED</th>
                    <th>LEFT</th>
                    <th>% OF GOAL</th>
                    <th>PROGRESS</th>
                    <th>MONTHLY NEEDED</th>
                    <th>TARGET DATE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($savingsFunds as $fund)
                    <tr>
                        <td style="font-weight: 700; color: #FF6B9D;">{{ $fund['name'] }}</td>
                        <td>${{ number_format($fund['goal'], 2) }}</td>
                        <td>${{ number_format($fund['saved'], 2) }}</td>
                        <td>${{ number_format($fund['left'], 2) }}</td>
                        <td>{{ $fund['percentage'] }}%</td>
                        <td>
                            <div class="progress-bar" style="width: 80px;">
                                <div class="progress-fill {{ $fund['percentage'] > 70 ? '' : ($fund['percentage'] > 40 ? 'orange' : 'pink') }}" style="width: {{ min(100, $fund['percentage']) }}%;"></div>
                            </div>
                        </td>
                        <td>${{ number_format($fund['monthly_left'], 0) }}</td>
                        <td>{{ $fund['estimate_date'] ?? 'Not set' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="savings-summary">
        <div class="savings-summary-title">SAVINGS FUNDS SUMMARY</div>
        <div style="text-align: center; padding: 40px; color: #666;">
            <div style="font-size: 48px; margin-bottom: 12px;">🎯</div>
            <p>No savings goals yet. <a href="/admin/financial/savings" style="color: #5DADE2;">Create your first savings goal</a></p>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Real data from database
    const billsData = @json($billsDistribution);
    const monthlyData = @json($monthlyData);
    const expenseByCategory = @json($expenseByCategory);
    const savingsVsExpenses = @json($savingsVsExpenses);
    const householdExpenses = @json($householdExpenses);
    const monthlySavingsDebts = @json($monthlySavingsDebts);
    const savingsOverYear = @json($savingsOverYear);
    const monthlyNeeds = @json($monthlyNeeds);

    // Bills Distribution Chart
    const billsCtx = document.getElementById('billsChart').getContext('2d');
    new Chart(billsCtx, {
        type: 'doughnut',
        data: {
            labels: billsData.labels || ['No data'],
            datasets: [{
                data: billsData.values || [1],
                backgroundColor: ['#FFB3D9', '#B3D9FF', '#B3FFB3', '#FFD9B3', '#D9B3FF'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                }
            }
        }
    });

    // Income vs Expenses Chart
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(incomeExpenseCtx, {
        type: 'bar',
        data: {
            labels: monthlyData.months || [],
            datasets: [
                {
                    label: 'Income',
                    data: monthlyData.income || [],
                    backgroundColor: '#B3D9FF',
                    borderRadius: 4
                },
                {
                    label: 'Expenses',
                    data: monthlyData.expenses || [],
                    backgroundColor: '#FFB3D9',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { labels: { font: { size: 11 } } }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Where Does My Money Go Chart
    const expenseLabels = expenseByCategory.map(e => e.category.charAt(0).toUpperCase() + e.category.slice(1).replace('_', ' '));
    const expenseValues = expenseByCategory.map(e => e.total);
    
    const moneyGoCtx = document.getElementById('moneyGoChart').getContext('2d');
    new Chart(moneyGoCtx, {
        type: 'doughnut',
        data: {
            labels: expenseLabels.length > 0 ? expenseLabels : ['No expenses'],
            datasets: [{
                data: expenseValues.length > 0 ? expenseValues : [1],
                backgroundColor: ['#FFB3D9', '#B3D9FF', '#B3FFB3', '#FFD9B3', '#D9B3FF', '#E6D9FF', '#B3FFD9', '#FFCCB3'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.parsed.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Budget Breakdown Chart (Savings vs Expenses)
    const budgetBreakdownCtx = document.getElementById('budgetBreakdownChart').getContext('2d');
    new Chart(budgetBreakdownCtx, {
        type: 'doughnut',
        data: {
            labels: ['Savings', 'Expenses'],
            datasets: [{
                data: [savingsVsExpenses.savings || 50, savingsVsExpenses.expenses || 50],
                backgroundColor: ['#B3FFB3', '#FFB3D9'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                }
            }
        }
    });

    // Household Expenses Chart
    const householdCtx = document.getElementById('householdExpensesChart').getContext('2d');
    new Chart(householdCtx, {
        type: 'bar',
        data: {
            labels: householdExpenses.months || [],
            datasets: [
                {
                    label: 'Debts',
                    data: householdExpenses.debts || [],
                    backgroundColor: '#FFB3D9',
                    borderRadius: 4
                },
                {
                    label: 'Savings',
                    data: householdExpenses.savings || [],
                    backgroundColor: '#B3FFB3',
                    borderRadius: 4
                },
                {
                    label: 'Subscriptions',
                    data: householdExpenses.subscriptions || [],
                    backgroundColor: '#FFD9B3',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'x',
            plugins: {
                legend: { labels: { font: { size: 11 } } }
            },
            scales: {
                y: { 
                    stacked: false, 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Monthly Savings/Debts Chart
    const monthlySavingsCtx = document.getElementById('monthlySavingsChart').getContext('2d');
    new Chart(monthlySavingsCtx, {
        type: 'bar',
        data: {
            labels: monthlySavingsDebts.months || [],
            datasets: [
                {
                    label: 'Savings',
                    data: monthlySavingsDebts.savings || [],
                    backgroundColor: '#B3FFB3',
                    borderRadius: 4
                },
                {
                    label: 'Debts',
                    data: monthlySavingsDebts.debts || [],
                    backgroundColor: '#FFB3D9',
                    borderRadius: 4
                },
                {
                    label: 'Threshold',
                    data: monthlySavingsDebts.threshold || [],
                    type: 'line',
                    borderColor: '#FFA500',
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { labels: { font: { size: 11 } } }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Savings Over The Year Chart
    const savingsOverYearCtx = document.getElementById('savingsOverYearChart').getContext('2d');
    new Chart(savingsOverYearCtx, {
        type: 'bar',
        data: {
            labels: savingsOverYear.months || [],
            datasets: [
                {
                    label: 'Monthly Savings',
                    data: savingsOverYear.monthly || [],
                    backgroundColor: '#FFB3D9',
                    borderRadius: 4
                },
                {
                    label: 'Cumulative',
                    data: savingsOverYear.cumulative || [],
                    type: 'line',
                    borderColor: '#B3D9FF',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#B3D9FF'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { labels: { font: { size: 11 } } }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Income vs Savings Trend Chart
    const incomeSavingsTrendCtx = document.getElementById('incomeSavingsTrendChart');
    if (incomeSavingsTrendCtx) {
        new Chart(incomeSavingsTrendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyData.months || [],
                datasets: [
                    {
                        label: 'Income',
                        data: monthlyData.income || [],
                        borderColor: '#B3D9FF',
                        backgroundColor: 'rgba(179, 217, 255, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Savings',
                        data: savingsOverYear.monthly || [],
                        borderColor: '#B3FFB3',
                        backgroundColor: 'rgba(179, 255, 179, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { font: { size: 11 } } }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Monthly Needs Chart
    const monthlyNeedsCtx = document.getElementById('monthlyNeedsChart').getContext('2d');
    new Chart(monthlyNeedsCtx, {
        type: 'bar',
        data: {
            labels: monthlyNeeds.months || [],
            datasets: [
                {
                    label: 'Needs',
                    data: monthlyNeeds.needs || [],
                    backgroundColor: '#FFD9B3',
                    borderRadius: 4
                },
                {
                    label: 'Threshold',
                    data: monthlyNeeds.threshold || [],
                    type: 'line',
                    borderColor: '#FF6B9D',
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { labels: { font: { size: 11 } } }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
