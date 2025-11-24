@extends('layouts.app')

@section('page-title', 'Edit Time Entry')

@section('content')
    <div class="card" style="max-width: 500px;">
        <h2>Edit Time Entry</h2>

        <form method="POST" action="/my-logs/{{ $entry->id }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="entry_date">Date</label>
                <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', $entry->entry_date->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $entry->start_time) }}" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $entry->end_time) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description">{{ old('description', $entry->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update Entry</button>
                <a href="/my-logs" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
