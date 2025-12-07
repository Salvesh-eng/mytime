@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')
<style>
    :root {
        --soft-pink: #FFB3D9;
        --soft-blue: #B3D9FF;
        --soft-green: #B3FFB3;
        --soft-orange: #FFD9B3;
        --soft-purple: #D9B3FF;
        --soft-peach: #FFCCB3;
        --soft-mint: #B3FFD9;
        --soft-lavender: #E6D9FF;
        --light-green: #C8E6C9;
        --light-pink: #F8BBD0;
    }

    .users-container {
        max-width: 100%;
        padding: 0;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(217, 179, 255, 0.2) 50%, rgba(179, 255, 217, 0.15) 100%);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        border: 1px solid rgba(179, 217, 255, 0.3);
        box-shadow: 0 4px 20px rgba(179, 217, 255, 0.15);
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title-icon {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        padding: 12px;
        border-radius: 14px;
        font-size: 24px;
    }

    .page-title h1 {
        font-size: 26px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .page-title p {
        color: #6B7280;
        font-size: 14px;
        margin: 4px 0 0 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
        text-decoration: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(179, 255, 179, 0.3);
        border: 1px solid rgba(179, 255, 179, 0.5);
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(179, 255, 179, 0.4);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-card.blue { border-top: 4px solid var(--soft-blue); }
    .stat-card.green { border-top: 4px solid var(--soft-green); }
    .stat-card.orange { border-top: 4px solid var(--soft-orange); }
    .stat-card.purple { border-top: 4px solid var(--soft-purple); }

    .stat-icon {
        font-size: 28px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Search and Filters */
    .filters-section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(179, 217, 255, 0.1) 100%);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid rgba(179, 217, 255, 0.3);
        box-shadow: 0 2px 12px rgba(179, 217, 255, 0.1);
    }

    .filters-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 1.5px solid rgba(179, 217, 255, 0.4);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--soft-blue);
        box-shadow: 0 0 0 3px rgba(179, 217, 255, 0.2);
    }

    .search-box::before {
        content: '🔍';
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
    }

    .filter-select {
        padding: 12px 16px;
        border: 1.5px solid rgba(217, 179, 255, 0.4);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        min-width: 150px;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--soft-purple);
        box-shadow: 0 0 0 3px rgba(217, 179, 255, 0.2);
    }

    /* Users Table */
    .users-table-container {
        background: white;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.15) 0%, rgba(217, 179, 255, 0.15) 100%);
    }

    .users-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #0F172A;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(179, 217, 255, 0.2);
    }

    .users-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .users-table tbody tr {
        transition: all 0.2s;
    }

    .users-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.05) 0%, rgba(217, 179, 255, 0.05) 100%);
    }

    /* User Info Cell */
    .user-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: white;
        overflow: hidden;
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar.blue { background: linear-gradient(135deg, var(--soft-blue) 0%, #60a5fa 100%); color: #0c4a6e; }
    .user-avatar.purple { background: linear-gradient(135deg, var(--soft-purple) 0%, #c084fc 100%); color: #6b21a8; }
    .user-avatar.green { background: linear-gradient(135deg, var(--soft-green) 0%, #4ade80 100%); color: #15803d; }
    .user-avatar.orange { background: linear-gradient(135deg, var(--soft-orange) 0%, #fb923c 100%); color: #9a3412; }
    .user-avatar.pink { background: linear-gradient(135deg, var(--soft-pink) 0%, #f472b6 100%); color: #9d174d; }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        color: #0F172A;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .user-email {
        font-size: 12px;
        color: #6B7280;
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-badge.admin {
        background: linear-gradient(135deg, rgba(255, 217, 179, 0.3) 0%, rgba(255, 204, 179, 0.3) 100%);
        color: #9a3412;
    }

    .role-badge.user {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.3) 0%, rgba(179, 255, 217, 0.3) 100%);
        color: #0c4a6e;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.active {
        background: linear-gradient(135deg, rgba(179, 255, 179, 0.3) 0%, rgba(179, 255, 217, 0.3) 100%);
        color: #15803d;
    }

    .status-badge.inactive {
        background: linear-gradient(135deg, rgba(229, 231, 235, 0.5) 0%, rgba(209, 213, 219, 0.5) 100%);
        color: #6B7280;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-dot.active { background: #16A34A; }
    .status-dot.inactive { background: #9CA3AF; }

    /* Password Cell */
    .password-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .password-masked {
        font-family: monospace;
        font-size: 14px;
        color: #6B7280;
        letter-spacing: 2px;
    }

    .password-toggle {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(217, 179, 255, 0.2) 100%);
        border: 1px solid rgba(179, 217, 255, 0.3);
        border-radius: 6px;
        padding: 4px 8px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }

    .password-toggle:hover {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.4) 0%, rgba(217, 179, 255, 0.4) 100%);
    }

    /* Join Date */
    .join-date {
        font-size: 13px;
        color: #6B7280;
    }

    /* Actions Cell */
    .actions-cell {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action.view {
        background: linear-gradient(135deg, var(--soft-blue) 0%, #a5d4ff 100%);
        color: #0c4a6e;
    }

    .btn-action.edit {
        background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%);
        color: #6b21a8;
    }

    .btn-action.activate {
        background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%);
        color: #15803d;
    }

    .btn-action.deactivate {
        background: linear-gradient(135deg, var(--soft-orange) 0%, var(--soft-peach) 100%);
        color: #9a3412;
    }

    .btn-action.reset {
        background: linear-gradient(135deg, var(--soft-pink) 0%, var(--light-pink) 100%);
        color: #9d174d;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(217, 179, 255, 0.1) 100%);
        border-radius: 20px;
        border: 2px dashed rgba(179, 217, 255, 0.3);
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6B7280;
        font-size: 14px;
        margin-bottom: 24px;
    }

    /* Pagination */
    .pagination-container {
        padding: 20px;
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pagination-container a,
    .pagination-container span {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-container a {
        background: white;
        border: 1px solid rgba(179, 217, 255, 0.4);
        color: #0c4a6e;
    }

    .pagination-container a:hover {
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(217, 179, 255, 0.2) 100%);
    }

    .pagination-container span.active {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--soft-lavender) 100%);
        color: #0c4a6e;
        border: 1px solid var(--soft-blue);
    }

    .pagination-container span:not(.active) {
        color: #9CA3AF;
    }

    /* Profile Picture Upload Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6B7280;
    }

    .upload-area {
        border: 2px dashed var(--soft-blue);
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.1) 0%, rgba(217, 179, 255, 0.1) 100%);
        cursor: pointer;
        transition: all 0.3s;
    }

    .upload-area:hover {
        border-color: var(--soft-purple);
        background: linear-gradient(135deg, rgba(179, 217, 255, 0.2) 0%, rgba(217, 179, 255, 0.2) 100%);
    }

    .upload-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }

    .upload-text {
        color: #6B7280;
        font-size: 14px;
    }
</style>

<div class="users-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <span class="page-title-icon">👥</span>
                <div>
                    <h1>User Management</h1>
                    <p>Manage users, roles, and permissions</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="/admin/users/create" class="btn-create">
                    <span>➕</span> Create New User
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $users->total() }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $users->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon">👑</div>
            <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
            <div class="stat-label">Admins</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">🆕</div>
            <div class="stat-value">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
            <div class="stat-label">New (30 days)</div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="search-box">
                <input type="text" id="searchUsers" placeholder="Search by name or email..." onkeyup="filterUsers()">
            </div>
            <select class="filter-select" id="roleFilter" onchange="filterUsers()">
                <option value="">All Roles</option>
                <option value="admin">👑 Admin</option>
                <option value="user">👤 User</option>
            </select>
            <select class="filter-select" id="statusFilter" onchange="filterUsers()">
                <option value="">All Status</option>
                <option value="active">✅ Active</option>
                <option value="inactive">⏸️ Inactive</option>
            </select>
        </div>
    </div>

    @if($users->count() > 0)
        <!-- Users Table -->
        <div class="users-table-container">
            <table class="users-table" id="usersTable">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>🔐 Password</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $avatarColors = ['blue', 'purple', 'green', 'orange', 'pink'];
                    @endphp
                    @foreach($users as $index => $user)
                        @php
                            $avatarColor = $avatarColors[$index % count($avatarColors)];
                            $initials = collect(explode(' ', $user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
                        @endphp
                        <tr data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}" data-status="{{ $user->status }}">
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar {{ $avatarColor }}" onclick="openUploadModal({{ $user->id }})" style="cursor: pointer;" title="Click to upload photo">
                                        @if($user->photo_url)
                                            <img src="{{ asset('storage/' . $user->photo_url) }}" alt="{{ $user->name }}">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge {{ $user->role }}">
                                    @if($user->role === 'admin')
                                        👑 Admin
                                    @else
                                        👤 User
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="password-cell">
                                    <span class="password-masked" id="pw-{{ $user->id }}">••••••••</span>
                                    <button type="button" class="password-toggle" onclick="togglePassword({{ $user->id }})" title="Show/Hide">
                                        👁️
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->status }}">
                                    <span class="status-dot {{ $user->status }}"></span>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="join-date">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="/admin/users/{{ $user->id }}/profile" class="btn-action view" title="View Profile">
                                        👤
                                    </a>
                                    <a href="/admin/users/{{ $user->id }}/edit" class="btn-action edit" title="Edit">
                                        ✏️
                                    </a>
                                    @if($user->status === 'active')
                                        <form method="POST" action="/admin/users/{{ $user->id }}/deactivate" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action deactivate" onclick="return confirm('Deactivate this user?')" title="Deactivate">
                                                ⏸️
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="/admin/users/{{ $user->id }}/activate" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action activate" title="Activate">
                                                ✅
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="/admin/users/{{ $user->id }}/reset-password" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action reset" onclick="return confirm('Reset password for this user?')" title="Reset Password">
                                            🔑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="pagination-container">
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
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <h3>No Users Yet</h3>
            <p>Create your first user to get started with user management.</p>
            <a href="/admin/users/create" class="btn-create">
                <span>➕</span> Create First User
            </a>
        </div>
    @endif
</div>

<!-- Profile Picture Upload Modal -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>📸 Upload Profile Picture</h3>
            <button class="modal-close" onclick="closeUploadModal()">×</button>
        </div>
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="upload-area" onclick="document.getElementById('profilePhoto').click()">
                <div class="upload-icon">📁</div>
                <div class="upload-text">Click to select an image<br><small>JPG, PNG up to 2MB</small></div>
            </div>
            <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" style="display: none;" onchange="handleFileSelect(event)">
            <div id="previewContainer" style="margin-top: 20px; text-align: center; display: none;">
                <img id="imagePreview" style="max-width: 150px; max-height: 150px; border-radius: 12px; border: 3px solid var(--soft-blue);">
            </div>
            <div style="margin-top: 20px; display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; padding: 12px; background: linear-gradient(135deg, var(--soft-green) 0%, var(--soft-mint) 100%); border: none; border-radius: 10px; font-weight: 600; color: #15803d; cursor: pointer;">
                    ✅ Upload
                </button>
                <button type="button" onclick="closeUploadModal()" style="padding: 12px 24px; background: #f3f4f6; border: none; border-radius: 10px; font-weight: 600; color: #6B7280; cursor: pointer;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentUserId = null;

    function filterUsers() {
        const searchValue = document.getElementById('searchUsers').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const role = row.getAttribute('data-role') || '';
            const status = row.getAttribute('data-status') || '';
            
            const matchesSearch = name.includes(searchValue) || email.includes(searchValue);
            const matchesRole = !roleFilter || role === roleFilter;
            const matchesStatus = !statusFilter || status === statusFilter;
            
            row.style.display = matchesSearch && matchesRole && matchesStatus ? '' : 'none';
        });
    }

    function togglePassword(userId) {
        const pwElement = document.getElementById('pw-' + userId);
        if (pwElement.textContent === '••••••••') {
            pwElement.textContent = '(encrypted)';
            pwElement.style.color = '#9d174d';
            pwElement.style.fontStyle = 'italic';
        } else {
            pwElement.textContent = '••••••••';
            pwElement.style.color = '#6B7280';
            pwElement.style.fontStyle = 'normal';
        }
    }

    function openUploadModal(userId) {
        currentUserId = userId;
        document.getElementById('uploadForm').action = '/admin/users/' + userId + '/upload-photo';
        document.getElementById('uploadModal').style.display = 'flex';
        document.getElementById('previewContainer').style.display = 'none';
        document.getElementById('profilePhoto').value = '';
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').style.display = 'none';
        currentUserId = null;
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    // Close modal when clicking outside
    document.getElementById('uploadModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUploadModal();
        }
    });
</script>
@endsection
