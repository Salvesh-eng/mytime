@extends('layouts.app')

@section('page-title', 'Donation Management')

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

    .donation-container {
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #D9B3FF 0%, #B3FFD9 100%);
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

    .page-header p {
        font-size: 14px;
        color: #666;
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

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .form-section {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--soft-purple);
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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--soft-purple);
        box-shadow: 0 0 0 3px rgba(217, 179, 255, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .donations-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .donations-table th {
        background: linear-gradient(135deg, #D9B3FF 0%, #B3FFD9 100%);
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: #333;
        border-bottom: 2px solid #ddd;
    }

    .donations-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .donations-table tbody tr:hover {
        background: #f9f9f9;
    }

    .donations-table .action-btn {
        padding: 6px 12px;
        margin-right: 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .donations-table .edit-btn {
        background: var(--soft-purple);
        color: #333;
    }

    .donations-table .delete-btn {
        background: var(--soft-pink);
        color: #333;
    }

    .donations-table .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .summary-card {
        background: linear-gradient(135deg, #D9B3FF 0%, #B3FFD9 100%);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .summary-card-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .summary-card-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #D9B3FF 0%, #B3FFD9 100%);
        color: #333;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #B3D9FF 0%, #B3FFB3 100%);
        color: #333;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .chart-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
    }

    .chart-canvas {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
    }

    @media (max-width: 1200px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .page-header {
            padding: 20px;
        }
        
        .page-header h1 {
            font-size: 26px;
        }
        
        .form-section {
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .page-header h1 {
            font-size: 22px;
        }
        
        .page-header {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .page-header p {
            font-size: 13px;
        }
        
        .back-button {
            padding: 8px 12px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .form-section {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .summary-card {
            padding: 12px;
        }
        
        .summary-card-label {
            font-size: 10px;
        }
        
        .summary-card-value {
            font-size: 20px;
        }
        
        .donations-table {
            font-size: 11px;
        }
        
        .donations-table th,
        .donations-table td {
            padding: 10px;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    }

    @media (max-width: 480px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .page-header h1 {
            font-size: 18px;
        }
        
        .page-header {
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 12px;
        }
        
        .back-button {
            padding: 8px 12px;
            font-size: 11px;
            margin-bottom: 12px;
        }
        
        .form-section {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .section-title {
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            font-size: 12px;
            margin-bottom: 6px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            font-size: 12px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 11px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .summary-card {
            padding: 10px;
        }
        
        .summary-card-label {
            font-size: 9px;
        }
        
        .summary-card-value {
            font-size: 18px;
        }
        
        .donations-table {
            font-size: 10px;
        }
        
        .donations-table th,
        .donations-table td {
            padding: 8px;
        }
        
        .donations-table .action-btn {
            padding: 4px 8px;
            font-size: 10px;
            margin-right: 2px;
        }
        
        .btn-group {
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-group .btn {
            flex: 1;
            padding: 10px 12px;
        }
        
        .chart-canvas {
            height: 200px;
        }
    }
</style>

<div class="donation-container">
    <a href="/admin/financial" class="back-button">← Back to Dashboard</a>

    <div class="page-header">
        <h1>❤️ Donation Management</h1>
        <p>Track and manage your charitable donations and contributions</p>
    </div>

    <!-- Record Donation Section -->
    <div class="form-section">
        <h2 class="section-title">1. Record New Donation</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Add a new donation to track your charitable giving</p>

        <form id="donationForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="organizationName">Organization Name *</label>
                    <input type="text" id="organizationName" placeholder="e.g., Red Cross" required>
                </div>
                <div class="form-group">
                    <label for="donationCategory">Category *</label>
                    <select id="donationCategory" required>
                        <option value="">Select Category</option>
                        <option value="charity">Charity</option>
                        <option value="religious">Religious</option>
                        <option value="education">Education</option>
                        <option value="health">Health & Medical</option>
                        <option value="environment">Environment</option>
                        <option value="animal">Animal Welfare</option>
                        <option value="community">Community</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="donationType">Type *</label>
                    <select id="donationType" required>
                        <option value="">Select Type</option>
                        <option value="monetary">Monetary</option>
                        <option value="goods">Goods/Items</option>
                        <option value="volunteer">Volunteer Time</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="donationAmount">Amount/Value *</label>
                    <input type="number" id="donationAmount" placeholder="$0.00" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="donationDate">Date *</label>
                    <input type="date" id="donationDate" required>
                </div>
                <div class="form-group">
                    <label for="isRecurring">Recurring Donation?</label>
                    <select id="isRecurring">
                        <option value="no">No</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annual">Annual</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="donationNotes">Notes</label>
                <textarea id="donationNotes" placeholder="Add any notes about this donation" rows="3"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="addDonation()">+ Record Donation</button>
        </form>

        <table class="donations-table" id="donationsTable" style="display: none;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Organization</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Recurring</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="donationsBody">
            </tbody>
        </table>
    </div>

    <!-- Donation Goals Section -->
    <div class="form-section">
        <h2 class="section-title">2. Set Donation Goals</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Set annual or monthly donation targets</p>

        <form id="donationGoalForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="goalType">Goal Type *</label>
                    <select id="goalType" required>
                        <option value="">Select Type</option>
                        <option value="annual">Annual Goal</option>
                        <option value="monthly">Monthly Goal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="goalAmount">Target Amount *</label>
                    <input type="number" id="goalAmount" placeholder="$0.00" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="goalCategory">Category Focus</label>
                    <select id="goalCategory">
                        <option value="">All Categories</option>
                        <option value="charity">Charity</option>
                        <option value="religious">Religious</option>
                        <option value="education">Education</option>
                        <option value="health">Health & Medical</option>
                        <option value="environment">Environment</option>
                        <option value="animal">Animal Welfare</option>
                        <option value="community">Community</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="setDonationGoal()">+ Set Goal</button>
        </form>
    </div>

    <!-- Summary Section -->
    <div class="form-section">
        <h2 class="section-title">3. Donation Summary</h2>
        
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-label">Total Donations</div>
                <div class="summary-card-value" id="totalDonations">0</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Total Amount Donated</div>
                <div class="summary-card-value" id="totalDonationAmount">$0</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Average Donation</div>
                <div class="summary-card-value" id="averageDonation">$0</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-label">Recurring Donations</div>
                <div class="summary-card-value" id="recurringCount">0</div>
            </div>
        </div>

        <table class="donations-table" style="margin-top: 30px;">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Count</th>
                    <th>Total Amount</th>
                    <th>Average</th>
                    <th>% of Total</th>
                </tr>
            </thead>
            <tbody id="categoryBreakdownBody">
            </tbody>
        </table>
    </div>

    <!-- Donation Distribution Chart -->
    <div class="chart-container">
        <h2 class="chart-title">4. Donation Distribution by Category</h2>
        <div class="chart-canvas">
            <canvas id="donationDistributionChart" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Tax Deduction Section -->
    <div class="form-section">
        <h2 class="section-title">5. Tax Deduction Summary</h2>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Track donations for tax purposes</p>

        <table class="donations-table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Total Donations</th>
                    <th>Eligible for Tax Deduction</th>
                    <th>Estimated Tax Benefit</th>
                </tr>
            </thead>
            <tbody id="taxDeductionBody">
            </tbody>
        </table>
    </div>

    <!-- Action Buttons -->
    <div class="form-section">
        <div class="btn-group">
            <button class="btn btn-primary" onclick="saveDonationData()">💾 Save Donation Data</button>
            <button class="btn btn-secondary" onclick="exportDonationReport()">📊 Export Report</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let donations = [];
    let donationGoals = [];

    // Add donation
    function addDonation() {
        const organizationName = document.getElementById('organizationName').value;
        const category = document.getElementById('donationCategory').value;
        const type = document.getElementById('donationType').value;
        const amount = parseFloat(document.getElementById('donationAmount').value);
        const date = document.getElementById('donationDate').value;
        const isRecurring = document.getElementById('isRecurring').value;
        const notes = document.getElementById('donationNotes').value;

        if (!organizationName || !category || !type || !amount || !date) {
            alert('Please fill in all required fields');
            return;
        }

        // Save to database immediately
        fetch('/admin/api/financial/donation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                organization_name: organizationName,
                category: category,
                type: type,
                amount: amount,
                date: date,
                is_recurring: isRecurring,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const donation = { 
                    id: data.data.id, 
                    organizationName, 
                    category, 
                    type, 
                    amount, 
                    date, 
                    isRecurring, 
                    notes 
                };
                donations.push(donation);

                document.getElementById('organizationName').value = '';
                document.getElementById('donationCategory').value = '';
                document.getElementById('donationType').value = '';
                document.getElementById('donationAmount').value = '';
                document.getElementById('donationDate').value = '';
                document.getElementById('isRecurring').value = 'no';
                document.getElementById('donationNotes').value = '';

                updateDonationsTable();
                updateSummary();
                alert('Donation recorded successfully!');
            } else {
                alert('Error saving donation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving donation');
        });
    }

    // Update donations table
    function updateDonationsTable() {
        const table = document.getElementById('donationsTable');
        const tbody = document.getElementById('donationsBody');

        if (donations.length === 0) {
            table.style.display = 'none';
            return;
        }

        table.style.display = 'table';
        tbody.innerHTML = '';

        donations.forEach(donation => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${donation.date}</td>
                <td>${donation.organizationName}</td>
                <td><span style="background: var(--soft-purple); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">${donation.category}</span></td>
                <td><span style="background: var(--soft-mint); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">${donation.type}</span></td>
                <td>$${donation.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${donation.isRecurring !== 'no' ? donation.isRecurring : '-'}</td>
                <td>
                    <button class="action-btn edit-btn" onclick="editDonation(${donation.id})">Edit</button>
                    <button class="action-btn delete-btn" onclick="deleteDonation(${donation.id})">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Delete donation
    function deleteDonation(id) {
        donations = donations.filter(d => d.id !== id);
        updateDonationsTable();
        updateSummary();
    }

    // Set donation goal
    function setDonationGoal() {
        const goalType = document.getElementById('goalType').value;
        const goalAmount = parseFloat(document.getElementById('goalAmount').value);
        const goalCategory = document.getElementById('goalCategory').value;

        if (!goalType || !goalAmount) {
            alert('Please fill in all required fields');
            return;
        }

        donationGoals.push({ goalType, goalAmount, goalCategory, id: Date.now() });
        alert('Donation goal set successfully!');

        document.getElementById('goalType').value = '';
        document.getElementById('goalAmount').value = '';
        document.getElementById('goalCategory').value = '';
    }

    // Update summary
    function updateSummary() {
        const totalDonations = donations.length;
        const totalAmount = donations.reduce((sum, d) => sum + d.amount, 0);
        const averageDonation = totalDonations > 0 ? totalAmount / totalDonations : 0;
        const recurringCount = donations.filter(d => d.isRecurring !== 'no').length;

        document.getElementById('totalDonations').textContent = totalDonations;
        document.getElementById('totalDonationAmount').textContent = '$' + totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('averageDonation').textContent = '$' + averageDonation.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('recurringCount').textContent = recurringCount;

        updateCategoryBreakdown();
        updateTaxDeduction();
        updateChart();
    }

    // Update category breakdown
    function updateCategoryBreakdown() {
        const tbody = document.getElementById('categoryBreakdownBody');
        tbody.innerHTML = '';

        const categoryTotals = {};
        const categoryCounts = {};

        donations.forEach(donation => {
            categoryTotals[donation.category] = (categoryTotals[donation.category] || 0) + donation.amount;
            categoryCounts[donation.category] = (categoryCounts[donation.category] || 0) + 1;
        });

        const totalAmount = Object.values(categoryTotals).reduce((a, b) => a + b, 0);

        Object.keys(categoryTotals).forEach(category => {
            const total = categoryTotals[category];
            const count = categoryCounts[category];
            const average = total / count;
            const percentage = totalAmount > 0 ? (total / totalAmount * 100).toFixed(1) : 0;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${category}</strong></td>
                <td>${count}</td>
                <td>$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>$${average.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${percentage}%</td>
            `;
            tbody.appendChild(row);
        });
    }

    // Update tax deduction
    function updateTaxDeduction() {
        const tbody = document.getElementById('taxDeductionBody');
        tbody.innerHTML = '';

        const currentYear = new Date().getFullYear();
        const yearTotals = {};

        donations.forEach(donation => {
            const year = new Date(donation.date).getFullYear();
            yearTotals[year] = (yearTotals[year] || 0) + donation.amount;
        });

        Object.keys(yearTotals).sort().forEach(year => {
            const total = yearTotals[year];
            const taxBenefit = (total * 0.25).toFixed(2); // Assuming 25% tax bracket

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${year}</strong></td>
                <td>$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>$${taxBenefit}</td>
            `;
            tbody.appendChild(row);
        });
    }

    // Update chart
    function updateChart() {
        const ctx = document.getElementById('donationDistributionChart');
        if (ctx.chart) {
            ctx.chart.destroy();
        }

        const categoryTotals = {};
        donations.forEach(donation => {
            categoryTotals[donation.category] = (categoryTotals[donation.category] || 0) + donation.amount;
        });

        ctx.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(categoryTotals),
                datasets: [{
                    data: Object.values(categoryTotals),
                    backgroundColor: ['#D9B3FF', '#B3FFD9', '#FFB3D9', '#B3D9FF', '#B3FFB3', '#FFD9B3', '#FFCCB3'],
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
                        labels: { font: { size: 12 } }
                    }
                }
            }
        });
    }

    // Save donation data
    function saveDonationData() {
        if (donations.length === 0) {
            alert('No donations to save!');
            return;
        }

        // Save each donation to the database
        donations.forEach(donation => {
            fetch('/admin/api/financial/donation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    organization_name: donation.organizationName,
                    category: donation.category,
                    type: donation.type,
                    amount: donation.amount,
                    date: donation.date,
                    is_recurring: donation.isRecurring,
                    notes: donation.notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Error saving donation:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        alert('Donation data saved successfully!');
    }

    // Export report
    function exportDonationReport() {
        alert('Donation report exported as PDF!');
    }

    // Load donations from database
    function loadDonations() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/admin/api/financial/donations', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            }
        })
            .then(response => {
                console.log('Load donations response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Loaded donations:', data);
                if (data.success && data.data && Array.isArray(data.data) && data.data.length > 0) {
                    donations = data.data.map(donation => ({
                        id: donation.id,
                        organizationName: donation.organizationName,
                        category: donation.category,
                        type: donation.type,
                        amount: parseFloat(donation.amount),
                        date: donation.date,
                        isRecurring: donation.isRecurring,
                        notes: donation.notes
                    }));
                    console.log('Mapped donations:', donations);
                    updateDonationsTable();
                    updateSummary();
                } else {
                    console.log('No donations found or error in response:', data);
                }
            })
            .catch(error => {
                console.error('Error loading donations:', error);
            });
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        loadDonations();
        updateChart();
    });
</script>
@endsection
