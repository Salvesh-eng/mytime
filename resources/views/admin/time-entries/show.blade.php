@extends('layouts.app')

@section('page-title', 'Time Entry Details')

@section('content')
    <div class="card" style="max-width: 600px;">
        <h2>Time Entry Details</h2>

        <div style="margin-bottom: 20px;">
            <p><strong>User:</strong> {{ $entry->user->name }}</p>
            <p><strong>Date:</strong> {{ $entry->entry_date->format('M d, Y') }}</p>
            <p><strong>Start Time:</strong> {{ $entry->start_time }}</p>
            <p><strong>End Time:</strong> {{ $entry->end_time }}</p>
            <p><strong>Hours:</strong> {{ number_format($entry->hours, 2) }}</p>
            <p><strong>Description:</strong> {{ $entry->description ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge badge-{{ $entry->status }}">{{ ucfirst($entry->status) }}</span></p>
            @if($entry->admin_comment)
                <p><strong>Admin Comment:</strong> {{ $entry->admin_comment }}</p>
            @endif
        </div>

        @if($entry->status === 'pending')
            <div style="margin-bottom: 20px; padding: 15px; background-color: #fff3cd; border-radius: 4px;">
                <h3 style="margin-bottom: 15px;">Approve or Reject</h3>

                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <form method="POST" action="/admin/time-entries/{{ $entry->id }}/approve" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>

                    <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectForm').style.display = 'block'">Reject</button>
                </div>

                <form method="POST" action="/admin/time-entries/{{ $entry->id }}/reject" id="rejectForm" style="display: none;">
                    @csrf
                    <div class="form-group">
                        <label for="admin_comment">Rejection Reason</label>
                        <textarea id="admin_comment" name="admin_comment" required></textarea>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-danger">Reject Entry</button>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('rejectForm').style.display = 'none'">Cancel</button>
                    </div>
                </form>
            </div>
        @else
            <div style="margin-bottom: 20px; padding: 15px; background-color: #f0f0f0; border-radius: 4px;">
                <h3 style="margin-bottom: 15px;">Add Comment</h3>

                <form method="POST" action="/admin/time-entries/{{ $entry->id }}/comment">
                    @csrf
                    <div class="form-group">
                        <label for="admin_comment">Comment</label>
                        <textarea id="admin_comment" name="admin_comment" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </form>
            </div>
        @endif

        <a href="/admin/time-entries" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
