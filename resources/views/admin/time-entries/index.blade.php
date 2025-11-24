@extends('layouts.app')

@section('page-title', 'Time Entries Management')

@section('content')
    <div class="filter-form">
        <h3 style="margin-bottom: 15px;">Filter</h3>
        <form method="GET" action="/admin/time-entries">
            <div class="form-row">
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select id="user_id" name="user_id">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date_from">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="form-group">
                    <label for="date_to">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="/admin/time-entries" class="btn btn-secondary">Clear</a>
        </form>
    </div>

    <div class="card">
        <h2>Time Entries</h2>
        @if($entries->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                            <td>{{ $entry->user->name }}</td>
                            <td>{{ $entry->start_time }}</td>
                            <td>{{ $entry->end_time }}</td>
                            <td>{{ number_format($entry->hours, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $entry->status }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="/admin/time-entries/{{ $entry->id }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                @if($entries->onFirstPage())
                    <span>← Previous</span>
                @else
                    <a href="{{ $entries->previousPageUrl() }}">← Previous</a>
                @endif

                @foreach($entries->getUrlRange(1, $entries->lastPage()) as $page => $url)
                    @if($page == $entries->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($entries->hasMorePages())
                    <a href="{{ $entries->nextPageUrl() }}">Next →</a>
                @else
                    <span>Next →</span>
                @endif
            </div>
        @else
            <p>No time entries found.</p>
        @endif
    </div>
@endsection
