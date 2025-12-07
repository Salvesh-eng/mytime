@extends('layouts.app')

@section('page-title', 'Financial Transactions')

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

    .transactions-container {
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
    }

    .page-header {
        background: white;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .header-controls {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .control-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .control-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
    }

    select, input {
        padding: 10px 12px;
        border: 1px solid #DDD;
        border-radius: 6px;
        font-size: 13px;
        background: white;
        cursor: pointer;
    }

    select:focus, input:focus {
        outline: none;
        border-color: #5DADE2;
        box-shadow: 0 0 0 3px rgba(93, 173, 226, 0.1);
    }

    /* Financial Overview Cards */
    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .overview-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-top: 4px solid #4CAF50;
    }

    .overview-card.income {
        border-top-color: #5DADE2;
        background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    }

    .overview-card.expense {
        border-top-color: #FF6B9D;
        background: linear-gradient(135deg, #FCE4EC 0%, #F8BBD0 100%);
    }

    .overview-card.bills {
        border-top-color: #FFA500;
        background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
    }

    .overview-card.savings {
        border-top-color: #4CAF50;
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
    }

    .overview-card.debt {
        border-top-color: #FF6347;
        background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
    }

    .card-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .card-sublabel {
        font-size: 11px;
        color: #999;
    }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .chart-card {
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

    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 15px;
    }

    /* Transaction Cards Grid */
    .transactions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .transaction-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .transaction-card-header {
        padding: 15px 20px;
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        border-bottom: 2px solid #4CAF50;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .transaction-card-header.bills {
        background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
        border-bottom-color: #FFA500;
    }

    .transaction-card-header.expenses {
        background: linear-gradient(135deg, #FCE4EC 0%, #F8BBD0 100%);
        border-bottom-color: #FF6B9D;
    }

    .transaction-card-header.debt {
        background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
        border-bottom-color: #FF6347;
    }

    .transaction-card-header.savings {
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        border-bottom-color: #4CAF50;
    }

    .transaction-card-header.income {
        background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
        border-bottom-color: #5DADE2;
    }

    .card-header-title {
        font-size: 14px;
        font-weight: 700;
        color: #333;
    }

    .card-header-icon {
        font-size: 20px;
    }

    .transaction-card-body {
        padding: 20px;
    }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #E0E0E0;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .item-details {
        display: flex;
        gap: 15px;
        font-size: 11px;
        color: #999;
    }

    .item-amount {
        font-size: 13px;
        font-weight: 700;
        color: #333;
        text-align: right;
        min-width: 80px;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #E0E0E0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50 0%, #66BB6A 100%);
        border-radius: 3px;
    }

    .progress-fill.orange {
        background: linear-gradient(90deg, #FFA500 0%, #FFB74D 100%);
    }

    .progress-fill.pink {
        background: linear-gradient(90deg, #FF6B9D 0%, #FF8FB3 100%);
    }

    .progress-fill.red {
        background: linear-gradient(90deg, #FF6347 0%, #FF7F50 100%);
    }

    .progress-fill.blue {
        background: linear-gradient(90deg, #5DADE2 0%, #85C1E2 100%);
    }

    .add-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .card-footer {
        padding: 15px 20px;
        background: #F9F9F9;
        border-top: 1px solid #E0E0E0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-total {
        font-size: 13px;
        font-weight: 700;
        color: #333;
    }

    .card-total-value {
        font-size: 16px;
        font-weight: 700;
        color: #4CAF50;
    }

    .card-total-value.orange {
        color: #FFA500;
    }

    .card-total-value.pink {
        color: #FF6B9D;
    }

    .card-total-value.red {
        color: #FF6347;
    }

    .card-total-value.blue {
        color: #5DADE2;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .charts-section {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-controls {
            width: 100%;
            flex-direction: column;
        }

        .charts-section {
            grid-template-columns: 1fr;
        }

        .transactions-grid {
            grid-template-columns: 1fr;
        }

        .overview-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }
</style>

<div class="transactions-container">
    <!-- Page Header with Controls -->
    <div class="page-header">
        <div class="page-title">Financial Transactions</div>
        <div class="header-controls">
            <div class="control-group">
                <label class="control-label">Budget Period</label>
                <select id="budgetPeriod" onchange="updateDashboard()">
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="control-group">
                <label class="control-label">Currency</label>
                <select id="currency" onchange="updateCurrency()">
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                    <option value="INR">INR (₹)</option>
                </select>
            </div>
            <div class="control-group">
                <label class="control-label">Month</label>
                <input type="month" id="monthPicker" value="2024-01" onchange="updateDashboard()">
            </div>
        </div>
    </div>

    <!-- Financial Overview Cards -->
    <div class="overview-cards">
        <div class="overview-card income">
            <div class="card-label">Total Income</div>
            <div class="card-value" id="totalIncome">$8,680</div>
            <div class="card-sublabel">This month</div>
        </div>
        <div class="overview-card expense">
            <div class="card-label">Total Expenses</div>
            <div class="card-value" id="totalExpenses">$6,230</div>
            <div class="card-sublabel">This month</div>
        </div>
        <div class="overview-card bills">
            <div class="card-label">Total Bills</div>
            <div class="card-value" id="totalBills">$1,850</div>
            <div class="card-sublabel">This month</div>
        </div>
        <div class="overview-card savings">
            <div class="card-label">Total Savings</div>
            <div class="card-value" id="totalSavings">$1,500</div>
            <div class="card-sublabel">This month</div>
        </div>
        <div class="overview-card debt">
            <div class="card-label">Total Debt</div>
            <div class="card-value" id="totalDebt">$500</div>
            <div class="card-sublabel">This month</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Financial Overview Chart -->
        <div class="chart-card">
            <div class="chart-title">Financial Overview</div>
            <div class="chart-container">
                <canvas id="financialOverviewChart"></canvas>
            </div>
        </div>

        <!-- Cash Flow Summary Chart -->
        <div class="chart-card">
            <div class="chart-title">Cash Flow Summary (Last 12 Months)</div>
            <div class="chart-container">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Transaction Cards Grid -->
    <div class="transactions-grid">
        <!-- Income Card -->
        <div class="transaction-card">
            <div class="transaction-card-header income">
                <div class="card-header-title">💰 Income</div>
                <div class="card-header-icon">📥</div>
            </div>
            <div class="transaction-card-body">
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Salary</div>
                        <div class="item-details">
                            <span>Planned: $8,000</span>
                            <span>Actual: $8,680</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill blue" style="width: 108.5%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">+$8,680</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Freelance</div>
                        <div class="item-details">
                            <span>Planned: $0</span>
                            <span>Actual: $0</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill blue" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">$0</div>
                </div>
            </div>
            <div class="card-footer">
                <div class="card-total">Total Income</div>
                <div class="card-total-value blue">$8,680</div>
            </div>
        </div>

        <!-- Expenses Card -->
        <div class="transaction-card">
            <div class="transaction-card-header expenses">
                <div class="card-header-title">🛒 Expenses</div>
                <div class="card-header-icon">📤</div>
            </div>
            <div class="transaction-card-body">
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Groceries</div>
                        <div class="item-details">
                            <span>Planned: $400</span>
                            <span>Actual: $450</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 112.5%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$450</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Dining Out</div>
                        <div class="item-details">
                            <span>Planned: $300</span>
                            <span>Actual: $280</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 93.3%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$280</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Entertainment</div>
                        <div class="item-details">
                            <span>Planned: $200</span>
                            <span>Actual: $150</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 75%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$150</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Shopping</div>
                        <div class="item-details">
                            <span>Planned: $500</span>
                            <span>Actual: $620</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 124%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$620</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Transportation</div>
                        <div class="item-details">
                            <span>Planned: $300</span>
                            <span>Actual: $350</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 116.7%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$350</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Utilities</div>
                        <div class="item-details">
                            <span>Planned: $200</span>
                            <span>Actual: $200</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$200</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Healthcare</div>
                        <div class="item-details">
                            <span>Planned: $150</span>
                            <span>Actual: $180</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill pink" style="width: 120%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$180</div>
                </div>
            </div>
            <div class="card-footer">
                <div class="card-total">Total Expenses</div>
                <div class="card-total-value pink">-$2,230</div>
            </div>
        </div>

        <!-- Bills Card -->
        <div class="transaction-card">
            <div class="transaction-card-header bills">
                <div class="card-header-title">📋 Bills</div>
                <div class="card-header-icon">💳</div>
            </div>
            <div class="transaction-card-body">
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Rent</div>
                        <div class="item-details">
                            <span>Planned: $1,200</span>
                            <span>Actual: $1,200</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill orange" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$1,200</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Internet</div>
                        <div class="item-details">
                            <span>Planned: $50</span>
                            <span>Actual: $50</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill orange" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$50</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Phone Bill</div>
                        <div class="item-details">
                            <span>Planned: $40</span>
                            <span>Actual: $40</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill orange" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$40</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Insurance</div>
                        <div class="item-details">
                            <span>Planned: $300</span>
                            <span>Actual: $300</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill orange" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$300</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Subscriptions</div>
                        <div class="item-details">
                            <span>Planned: $60</span>
                            <span>Actual: $60</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill orange" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$60</div>
                </div>
            </div>
            <div class="card-footer">
                <div class="card-total">Total Bills</div>
                <div class="card-total-value orange">-$1,650</div>
            </div>
        </div>

        <!-- Savings Card -->
        <div class="transaction-card">
            <div class="transaction-card-header savings">
                <div class="card-header-title">🏦 Savings</div>
                <div class="card-header-icon">💰</div>
            </div>
            <div class="transaction-card-body">
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Emergency Fund</div>
                        <div class="item-details">
                            <span>Planned: $800</span>
                            <span>Actual: $850</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 106.25%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">+$850</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Vacation Fund</div>
                        <div class="item-details">
                            <span>Planned: $400</span>
                            <span>Actual: $400</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">+$400</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Investment</div>
                        <div class="item-details">
                            <span>Planned: $300</span>
                            <span>Actual: $250</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 83.3%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">+$250</div>
                </div>
            </div>
            <div class="card-footer">
                <div class="card-total">Total Savings</div>
                <div class="card-total-value">+$1,500</div>
            </div>
        </div>

        <!-- Debt Card -->
        <div class="transaction-card">
            <div class="transaction-card-header debt">
                <div class="card-header-title">💳 Debt</div>
                <div class="card-header-icon">📊</div>
            </div>
            <div class="transaction-card-body">
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Credit Card Payment</div>
                        <div class="item-details">
                            <span>Planned: $300</span>
                            <span>Actual: $300</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill red" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$300</div>
                </div>
                <div class="transaction-item">
                    <div class="item-info">
                        <div class="item-name">Loan Payment</div>
                        <div class="item-details">
                            <span>Planned: $200</span>
                            <span>Actual: $200</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill red" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="item-amount">-$200</div>
                </div>
            </div>
            <div class="card-footer">
                <div class="card-total">Total Debt Payments</div>
                <div class="card-total-value red">-$500</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Financial Overview Chart (Doughnut)
    const financialCtx = document.getElementById('financialOverviewChart').getContext('2d');
    const financialChart = new Chart(financialCtx, {
        type: 'doughnut',
        data: {
            labels: ['Income', 'Expenses', 'Bills', 'Savings', 'Debt'],
            datasets: [{
                data: [8680, 2230, 1650, 1500, 500],
                backgroundColor: ['#B3D9FF', '#FFB3D9', '#FFD9B3', '#B3FFB3', '#FFCDD2'],
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
                    labels: { font: { size: 12 }, padding: 15 }
                }
            }
        }
    });

    // Cash Flow Summary Chart (Line)
    const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlowChart = new Chart(cashFlowCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Income',
                    data: [8680, 8680, 8680, 8680, 8680, 8680, 8680, 8680, 8680, 8680, 8680, 8680],
                    borderColor: '#5DADE2',
                    backgroundColor: 'rgba(93, 173, 226, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#5DADE2'
                },
                {
                    label: 'Expenses',
                    data: [2230, 2150, 2300, 2200, 2250, 2100, 2350, 2200, 2280, 2150, 2200, 2100],
                    borderColor: '#FF6B9D',
                    backgroundColor: 'rgba(255, 107, 157, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#FF6B9D'
                },
                {
                    label: 'Net Cash Flow',
                    data: [6450, 6530, 6380, 6480, 6430, 6580, 6330, 6480, 6400, 6530, 6480, 6580],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#4CAF50'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 }, padding: 15 }
                }
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

    // Update Dashboard Function
    function updateDashboard() {
        const period = document.getElementById('budgetPeriod').value;
        const month = document.getElementById('monthPicker').value;
        console.log('Dashboard updated:', { period, month });
    }

    // Update Currency Function
    function updateCurrency() {
        const currency = document.getElementById('currency').value;
        const symbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'INR': '₹'
        };
        console.log('Currency updated to:', currency, symbols[currency]);
    }
</script>
@endsection
