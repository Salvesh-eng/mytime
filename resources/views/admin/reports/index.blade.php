@extends('layouts.app')

@section('page-title', 'Reports')

@section('content')
    <div class="filter-form">
        <h3 style="margin-bottom: 15px;">Filter by Date Range</h3>
        <form method="GET" action="/admin/reports">
            <div class="form-row">
                <div class="form-group">
                    <label for="date_from">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="date_to">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="{{ $dateTo->format('Y-m-d') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="/admin/reports/export-csv?date_from={{ $dateFrom->format('Y-m-d') }}&date_to={{ $dateTo->format('Y-m-d') }}" class="btn btn-success">Export CSV</a>
        </form>
    </div>

    <div class="card">
        <h2>Hours by User</h2>
        @if($hoursByUser->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hoursByUser as $record)
                        <tr>
                            <td>{{ $record->name }}</td>
                            <td>{{ number_format($record->total_hours, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available.</p>
        @endif
    </div>

    <div class="card">
        <h2>Hours by Date</h2>
        @if($hoursByDate->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hoursByDate as $record)
                        <tr>
                            <td>{{ $record->entry_date->format('M d, Y') }}</td>
                            <td>{{ number_format($record->total_hours, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available.</p>
        @endif
    </div>
@endsection
