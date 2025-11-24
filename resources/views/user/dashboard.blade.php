@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Last Updated Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Your Dashboard</h1>
            <p style="color: #6B7280; font-size: 14px;">Track your time and manage your entries</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/add-time" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, #16A34A 0%, #15803d 100%); color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);">▶ Quick Timer</a>
            <div style="background: white; padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Last updated</div>
                <div style="font-size: 14px; font-weight: 600; color: #2563EB;" id="last-updated-time">Just now</div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <span style="font-weight: 600; color: #0F172A; font-size: 14px;">Filter by date:</span>
            <button onclick="setDateRange('today')" class="date-range-btn" data-range="today" style="padding: 8px 16px; border: 2px solid #2563EB; background-color: #2563EB; color: white; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">Today</button>
            <button onclick="setDateRange('week')" class="date-range-btn" data-range="week" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">This Week</button>
            <button onclick="setDateRange('month')" class="date-range-btn" data-range="month" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">This Month</button>
            <button onclick="setDateRange('all')" class="date-range-btn" data-range="all" style="padding: 8px 16px; border: 2px solid #e5e7eb; background-color: white; color: #0F172A; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">All Time</button>
            <div style="margin-left: auto; display: flex; gap: 8px; align-items: center;">
                <input type="date" id="custom-date-start" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                <span style="color: #6B7280;">to</span>
                <input type="date" id="custom-date-end" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                <button onclick="setDateRange('custom')" style="padding: 8px 16px; border: 2px solid #06B6D4; background-color: #06B6D4; color: white; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">Apply</button>
            </div>
        </div>
    </div>

    <div class="stats-grid" id="stats-container">
        <!-- Hours This Week Card (Green) -->
        <div style="background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); border-radius: 12px; border: 2px solid #16a34a; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.1);">
            <h3 style="color: #15803d; font-size: 13px; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">⏱️ Hours This Week</h3>
            <div style="font-size: 32px; font-weight: 700; color: #16a34a;" data-metric="hoursThisWeek">{{ number_format($hoursThisWeek, 2) }}</div>
            <div style="font-size: 12px; color: #16a34a; margin-top: 12px; font-weight: 600;" data-comparison="hoursThisWeek">↑ 8% vs last week</div>
        </div>
        <!-- Today's Status Card (Blue/Cyan) -->
        <div style="background: linear-gradient(135deg, #cffafe 0%, #ecf8ff 100%); border-radius: 12px; border: 2px solid #06b6d4; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(6, 182, 212, 0.1);">
            <h3 style="color: #164e63; font-size: 13px; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">📋 Today's Status</h3>
            <div class="value" data-metric="todayStatus" style="font-size: 18px; color: #06b6d4;">
                @if($todayEntry)
                    <span class="badge badge-{{ $todayEntry->status }}" style="background-color: #06b6d4; color: white; padding: 6px 12px; border-radius: 20px; font-weight: 600;">{{ ucfirst($todayEntry->status) }}</span>
                @else
                    <span style="color: #164e63; font-weight: 600;">No Entry</span>
                @endif
            </div>
            <div style="font-size: 12px; color: #06b6d4; margin-top: 12px; font-weight: 600;" data-comparison="todayStatus">Started at 09:00 AM</div>
        </div>
    </div>

    <!-- Project Completion Graphs Section -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- Current Month Graph -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); min-height: 350px;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px;">📊 Projects This Month</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Current Year Graph -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 24px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); min-height: 350px;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px;">📈 Projects This Year</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Quick Actions</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="/add-time" class="btn btn-primary">Add Time Entry</a>
            <a href="/my-logs" class="btn btn-secondary">View All Logs</a>
        </div>
    </div>

    <div class="card">
        <h2>Recent Entries</h2>
        @if($recentEntries->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEntries as $entry)
                        <tr>
                            <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                            <td>{{ $entry->start_time }}</td>
                            <td>{{ $entry->end_time }}</td>
                            <td>{{ number_format($entry->hours, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $entry->status }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                            <td>
                                @if($entry->status === 'pending')
                                    <a href="/my-logs/{{ $entry->id }}/edit" class="btn btn-secondary btn-sm">Edit</a>
                                @endif
            </td>
        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 48px 20px; background: linear-gradient(135deg, #f0f9ff 0%, #fef3c7 100%); border-radius: 12px; border: 1px dashed #cbd5e1;">
                <div style="font-size: 48px; margin-bottom: 16px;">⏱️</div>
                <h3 style="font-size: 18px; color: #0F172A; font-weight: 600; margin-bottom: 8px;">Start Tracking Your Time</h3>
                <p style="color: #6B7280; font-size: 14px; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">You haven't logged any time entries yet. Begin tracking your work now to monitor your productivity and time management.</p>
                <a href="/add-time" style="display: inline-block; padding: 12px 24px; background-color: #2563EB; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; margin-right: 12px;">▶ Start Tracking Time</a>
                <a href="/my-logs" style="display: inline-block; padding: 12px 24px; background-color: #06B6D4; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s;">View All Logs</a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    let currentDateRange = 'today';
    let monthlyChartInstance = null;
    let yearlyChartInstance = null;
    
    // Auto-refresh dashboard metrics every 30 seconds
    const REFRESH_INTERVAL = 30000; // 30 seconds

    // Color palette for charts
    const colorPalette = [
        '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
        '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B88B', '#52C9A3',
        '#FF8C94', '#A8E6CF', '#FFD3B6', '#FFAAA5', '#FF8B94'
    ];

    function getRandomColor() {
        return colorPalette[Math.floor(Math.random() * colorPalette.length)];
    }

    function initializeCharts() {
        const monthlyData = @json($projectCompletionThisMonth);
        const yearlyData = @json($projectCompletionThisYear);

        // Initialize Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const monthlyLabels = monthlyData.map(d => d.day);
            const monthlyCounts = monthlyData.map(d => d.count);
            
            // Generate random colors for each day
            const monthlyColors = monthlyData.map(() => getRandomColor());
            
            if (monthlyChartInstance) {
                monthlyChartInstance.destroy();
            }

            monthlyChartInstance = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Projects Completed',
                        data: monthlyCounts,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: monthlyColors,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: monthlyColors,
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 12, weight: 'bold' },
                                color: '#0F172A',
                                padding: 15,
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            borderColor: '#2563EB',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return 'Projects: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { size: 11 },
                                color: '#6B7280'
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: { size: 11 },
                                color: '#6B7280'
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }

        // Initialize Yearly Chart
        const yearlyCtx = document.getElementById('yearlyChart');
        if (yearlyCtx) {
            const yearlyLabels = yearlyData.map(d => d.month);
            const yearlyCounts = yearlyData.map(d => d.count);
            
            // Generate random colors for each month
            const yearlyColors = yearlyData.map(() => getRandomColor());
            
            if (yearlyChartInstance) {
                yearlyChartInstance.destroy();
            }

            yearlyChartInstance = new Chart(yearlyCtx, {
                type: 'line',
                data: {
                    labels: yearlyLabels,
                    datasets: [{
                        label: 'Projects Completed',
                        data: yearlyCounts,
                        borderColor: '#16A34A',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: yearlyColors,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: yearlyColors,
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 12, weight: 'bold' },
                                color: '#0F172A',
                                padding: 15,
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            borderColor: '#16A34A',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return 'Projects: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { size: 11 },
                                color: '#6B7280'
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: { size: 11 },
                                color: '#6B7280'
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }
    }
    
    function setDateRange(range) {
        currentDateRange = range;
        
        // Update button styles
        document.querySelectorAll('.date-range-btn').forEach(btn => {
            if (btn.getAttribute('data-range') === range) {
                btn.style.borderColor = '#2563EB';
                btn.style.backgroundColor = '#2563EB';
                btn.style.color = 'white';
            } else {
                btn.style.borderColor = '#e5e7eb';
                btn.style.backgroundColor = 'white';
                btn.style.color = '#0F172A';
            }
        });
        
        // Update metrics based on date range
        updateDashboardMetrics();
    }
    
    function getDateRangeParams() {
        const today = new Date();
        let startDate, endDate = new Date().toISOString().split('T')[0];
        
        switch(currentDateRange) {
            case 'today':
                startDate = endDate;
                break;
            case 'week':
                const first = today.getDate() - today.getDay();
                startDate = new Date(today.setDate(first)).toISOString().split('T')[0];
                break;
            case 'month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                break;
            case 'custom':
                startDate = document.getElementById('custom-date-start').value;
                endDate = document.getElementById('custom-date-end').value;
                if (!startDate || !endDate) {
                    alert('Please select both start and end dates');
                    return null;
                }
                break;
            case 'all':
            default:
                startDate = null;
                endDate = null;
        }
        
        return { startDate, endDate };
    }
    
    function updateDashboardMetrics() {
        const params = getDateRangeParams();
        if (currentDateRange === 'custom' && !params) return;
        
        let url = '/api/user/dashboard-metrics';
        if (params && params.startDate) {
            url += `?startDate=${params.startDate}&endDate=${params.endDate}`;
        }
        
        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update hours this week
            const hoursElement = document.querySelector('[data-metric="hoursThisWeek"]');
            if (hoursElement) {
                const oldValue = hoursElement.textContent;
                const newValue = data.hoursThisWeek;
                
                if (oldValue !== newValue) {
                    hoursElement.style.opacity = '0.5';
                    hoursElement.style.transition = 'opacity 0.3s ease';
                    
                    setTimeout(() => {
                        hoursElement.textContent = newValue;
                        hoursElement.style.opacity = '1';
                    }, 150);
                }
            }
            
            // Update today's status
            const statusElement = document.querySelector('[data-metric="todayStatus"]');
            if (statusElement && data.todayStatus) {
                const oldStatus = statusElement.textContent.trim();
                const newStatus = data.todayStatus;
                
                if (!oldStatus.includes(newStatus)) {
                    statusElement.style.opacity = '0.5';
                    statusElement.style.transition = 'opacity 0.3s ease';
                    
                    setTimeout(() => {
                        // Create badge element
                        let badgeClass = 'badge';
                        if (newStatus.toLowerCase() === 'pending') {
                            badgeClass += ' badge-pending';
                        } else if (newStatus.toLowerCase() === 'approved') {
                            badgeClass += ' badge-approved';
                        } else if (newStatus.toLowerCase() === 'rejected') {
                            badgeClass += ' badge-rejected';
                        }
                        
                        statusElement.innerHTML = `<span class="${badgeClass}">${newStatus}</span>`;
                        statusElement.style.opacity = '1';
                    }, 150);
                }
            }
            
            // Update last updated timestamp
            const lastUpdated = document.getElementById('last-updated-time');
            if (lastUpdated) {
                lastUpdated.textContent = data.timestamp;
            }
        })
        .catch(error => console.error('Error fetching dashboard metrics:', error));
    }
    
    // Initial setup
    window.addEventListener('DOMContentLoaded', function() {
        // Initialize charts
        initializeCharts();
        // Set Today as default active button
        setDateRange('today');
    });
    
    // Set up auto-refresh interval
    setInterval(updateDashboardMetrics, REFRESH_INTERVAL);
</script>
@endsection