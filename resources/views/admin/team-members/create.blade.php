@extends('layouts.app')

@section('page-title', 'Add Team Member')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h2>Add New Team Member</h2>
        
        <form method="POST" action="/admin/team-members">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g., John Doe">
                @error('name')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="e.g., john@example.com">
                @error('email')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="role">Role/Position *</label>
                <input type="text" id="role" name="role" value="{{ old('role') }}" required placeholder="e.g., Developer, Designer">
                @error('role')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>⏸️ Inactive</option>
                </select>
                @error('status')<span style="color: #DC2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-success">Add Team Member</button>
                <a href="/admin/team-members" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
