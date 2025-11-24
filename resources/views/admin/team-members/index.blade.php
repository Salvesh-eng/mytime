@extends('layouts.app')

@section('page-title', 'Team Members')

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="margin: 0; color: #0F172A;">Team Members Management</h2>
    <a href="/admin/team-members/create" class="btn btn-success">+ Add Team Member</a>
</div>

@if($teamMembers->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teamMembers as $member)
                <tr>
                    <td><strong>{{ $member->name }}</strong></td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->role }}</td>
                    <td>
                        <span class="badge badge-{{ $member->status }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td>{{ $member->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="/admin/team-members/{{ $member->id }}/edit" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" action="/admin/team-members/{{ $member->id }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $teamMembers->links() }}
    </div>
@else
    <div class="card">
        <p style="text-align: center; color: #6B7280;">No team members yet. <a href="/admin/team-members/create" style="color: #2563EB; font-weight: 600;">Add one now</a></p>
    </div>
@endif
@endsection
