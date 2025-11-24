@extends('layouts.app')

@section('page-title', 'My Time Logs')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="/add-time" class="btn btn-primary">Add New Entry</a>
    </div>

    <div class="filter-form">
        <h3 style="margin-bottom: 15px;">Filter</h3>
        <form method="GET" action="/my-logs">
            <div class="form-row">
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
            <a href="/my-logs" class="btn btn-secondary">Clear</a>
        </form>
    </div>

    <div class="card">
        <h2>My Time Logs</h2>
        @if($entries->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                            <td>{{ $entry->start_time }}</td>
                            <td>{{ $entry->end_time }}</td>
                            <td>{{ number_format($entry->hours, 2) }}</td>
                            <td>{{ $entry->description ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $entry->status }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/my-logs/{{ $entry->id }}" class="btn btn-primary btn-sm">View</a>
                                    @if($entry->status === 'pending')
                                        <a href="/my-logs/{{ $entry->id }}/edit" class="btn btn-secondary btn-sm">Edit</a>
                                        <form method="POST" action="/my-logs/{{ $entry->id }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this entry?')">Delete</button>
                                        </form>
                                    @endif
                                </div>
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
            <p>No time entries yet. <a href="/add-time">Add your first entry</a></p>
        @endif
    </div>
@endsection
