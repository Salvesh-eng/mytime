@extends('layouts.app')

@section('page-title', 'Add Time Entry')

@section('content')
    <div class="card" style="max-width: 500px;">
        <h2>Add Time Entry</h2>

        <form method="POST" action="/add-time">
            @csrf

            <div class="form-group">
                <label for="entry_date">Date</label>
                <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Add Entry</button>
                <a href="/dashboard" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Auto-calculate hours when times change
        document.getElementById('start_time').addEventListener('change', calculateHours);
        document.getElementById('end_time').addEventListener('change', calculateHours);

        function calculateHours() {
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;

            if (startTime && endTime) {
                const start = new Date(`2000-01-01 ${startTime}`);
                const end = new Date(`2000-01-01 ${endTime}`);

                if (end < start) {
                    end.setDate(end.getDate() + 1);
                }

                const hours = (end - start) / (1000 * 60 * 60);
                console.log(`Hours: ${hours.toFixed(2)}`);
            }
        }
    </script>
@endsection
