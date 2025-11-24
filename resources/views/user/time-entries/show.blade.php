@extends('layouts.app')

@section('page-title', 'Time Entry Details')

@section('content')
    <div class="card" style="max-width: 600px;">
        <h2>Time Entry Details</h2>

        <div style="margin-bottom: 20px;">
            <p><strong>Date:</strong> {{ $entry->entry_date->format('M d, Y') }}</p>
            <p><strong>Start Time:</strong> {{ $entry->start_time }}</p>
            <p><strong>End Time:</strong> {{ $entry->end_time }}</p>
            <p><strong>Hours:</strong> {{ number_format($entry->hours, 2) }}</p>
            <p><strong>Description:</strong> {{ $entry->description ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge badge-{{ $entry->status }}">{{ ucfirst($entry->status) }}</span></p>
            @if($entry->admin_comment)
                <div style="background-color: #f0f0f0; padding: 15px; border-radius: 4px; margin-top: 15px;">
                    <p><strong>Admin Comment:</strong></p>
                    <p>{{ $entry->admin_comment }}</p>
                </div>
            @endif
        </div>

        <div style="display: flex; gap: 10px;">
            @if($entry->status === 'pending')
                <a href="/my-logs/{{ $entry->id }}/edit" class="btn btn-primary">Edit</a>
                <form method="POST" action="/my-logs/{{ $entry->id }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this entry?')">Delete</button>
                </form>
            @endif
            <a href="/my-logs" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@endsection
