@extends('layouts.app')

@section('page-title', $customReport->name)

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, rgba(179, 255, 217, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(255, 179, 217, 0.15) 100%);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(179, 255, 179, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);
        margin-bottom: 32px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-left h1 {
        font-size: 26px;
        color: #0F172A;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .header-left p {
        color: #6B7280;
        font-size: 14px;
        margin: 0;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #0F172A;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #f9fafb;
    }

    .chart-widget {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .chart-header h2 {
        margin: 0;
        font-size: 18px;
        color: #0F172A;
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    table th {
        padding: 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
        color: #0F172A;
    }

    table tbody tr:hover {
        background: #f9fafb;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        background: linear-gradient(135deg, #f0f9ff 0%, #fef3c7 100%);
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
        margin: 0;
    }
</style>

<!-- Header Section -->
<div class="header-section">
    <div class="header-content">
        <div class="header-left">
            <h1>{{ $customReport->name }}</h1>
            @if($customReport->description)
                <p>{{ $customReport->description }}</p>
            @endif
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.reports.export-pdf', $customReport) }}" class="btn btn-primary">📄 Export PDF</a>
            <a href="{{ route('admin.reports.export-excel', $customReport) }}" class="btn btn-primary">📊 Export Excel</a>
            <a href="{{ route('admin.reports.list') }}" class="btn btn-secondary">← Back</a>
        </div>
    </div>
</div>

<!-- Report Data -->
@if($data && count($data) > 0)
    @foreach($data as $metric => $records)
        <div class="chart-widget">
            <div class="chart-header">
                <h2>{{ ucfirst(str_replace('_', ' ', $metric)) }}</h2>
            </div>
            
            @if(is_array($records) && count($records) > 0)
                <table>
                    <thead>
                        <tr>
                            @foreach(array_keys((array)$records[0]) as $key)
                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                @foreach((array)$record as $value)
                                    <td>
                                        @if(is_array($value))
                                            {{ json_encode($value) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>No Data</h3>
                    <p>No data available for this metric.</p>
                </div>
            @endif
        </div>
    @endforeach
@else
    <div class="chart-widget">
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>No Report Data</h3>
            <p>No data available for this report.</p>
        </div>
    </div>
@endif

@endsection
