@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="/admin/users/create" class="btn btn-primary">Create New User</a>
    </div>

    <div class="card">
        <h2>All Users</h2>
        @if($users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                <span class="badge badge-{{ $user->status }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-primary btn-sm">Edit</a>
                                    @if($user->status === 'active')
                                        <form method="POST" action="/admin/users/{{ $user->id }}/deactivate" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Deactivate this user?')">Deactivate</button>
                                        </form>
                                    @else
                                        <form method="POST" action="/admin/users/{{ $user->id }}/activate" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Activate</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="/admin/users/{{ $user->id }}/reset-password" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Reset password for this user?')">Reset Password</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                @if($users->onFirstPage())
                    <span>← Previous</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}">← Previous</a>
                @endif

                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}">Next →</a>
                @else
                    <span>Next →</span>
                @endif
            </div>
        @else
            <p>No users found.</p>
        @endif
    </div>
@endsection
