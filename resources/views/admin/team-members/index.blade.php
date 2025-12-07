@extends('layouts.app')

@section('page-title', 'Team Members')

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

    .team-container {
        max-width: 100%;
        padding: 0;
    }

    .page-header {
        background: linear-gradient(135deg, rgba(217, 179, 255, 0.2) 0%, rgba(179, 217, 255, 0.2) 50%, rgba(179, 255, 217, 0.15) 100%);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        border: 1px solid rgba(217, 179, 255, 0.3);
        box-shadow: 0 4px 20px rgba(217, 179, 255, 0.15);
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
        background: linear-gradient(135deg, var(--soft-purple) 0%, var(--soft-lavender) 100%);
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

    .btn-add-member {
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

    .btn-add-member:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(179, 255, 179, 0.4);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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

    .stat-card.purple { border-top: 4px solid var(--soft-purple); }
    .stat-card.green { border-top: 4px solid var(--soft-green); }
    .stat-card.orange { border-top: 4px solid var(--soft-orange); }
    .stat-card.blue { border-top: 4px solid var(--soft-blue); }

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

    /* Team Members Table */
    .team-table-container {
        background: white;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .team-table {
        width: 100%;
        border-collapse: collapse;
    }

    .team-table thead {
        background: linear-gradient(135deg, rgba(217, 179, 255, 0.15) 0%, rgba(179, 217, 255, 0.15) 100%);
    }

    .team-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #0F172A;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(217, 179, 255, 0.2);
    }

    .team-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .team-table tbody tr {
        transition: all 0.2s;
    }

    .team-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(217, 179, 255, 0.05) 0%, rgba(179, 217, 255, 0.05) 100%);
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: white;
    }

    .member-avatar.purple { background: linear-gradient(135deg, var(--soft-purple) 0%, #c084fc 100%); color: #6b21a8; }
    .member-avatar.blue { background: linear-gradient(135deg, var(--soft-blue) 0%, #60a5fa 100%); color: #0c4a6e; }
    .member-avatar.green { background: linear-gradient(135deg, var(--soft-green) 0%, #4ade80 100%); color: #15803d; }
    .member-avatar.orange { background: linear-gradient(135deg, var(--soft-orange) 0%, #fb923c 100%); color: #9a3412; }
    .member-avatar.pink { background: linear-gradient(135deg, var(--soft-pink) 0%, #f472b6 100%); color: #9d174d; }

    .member-name {
        font-weight: 600;
        color: #0F172A;
        font-size: 14px;
    }

    .member-email {
        font-size: 12px;
        color: #6B7280;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-badge.developer { background: rgba(179, 217, 255, 0.3); color: #0c4a6e; }
    .role-badge.designer { background: rgba(255, 179, 217, 0.3); color: #9d174d; }
    .role-badge.manager { background: rgba(217, 179, 255, 0.3); color: #6b21a8; }
    .role-badge.default { background: rgba(179, 255, 179, 0.3); color: #15803d; }

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

    .join-date {
        font-size: 13px;
        color: #6B7280;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
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

    .btn-action.delete {
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
        background: linear-gradient(135deg, rgba(217, 179, 255, 0.1) 0%, rgba(179, 217, 255, 0.1) 100%);
        border-radius: 20px;
        border: 2px dashed rgba(217, 179, 255, 0.3);
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
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Pagination */
    .pagination-container {
        padding: 20px;
        display: flex;
        justify-content: center;
    }
</style>

<div class="team-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <span class="page-title-icon">👥</span>
                <div>
                    <h1>Team Members</h1>
                    <p>Manage your team, roles, and assignments</p>
                </div>
            </div>
            <a href="/admin/team-members/create" class="btn-add-member">
                <span>➕</span> Add Team Member
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $teamMembers->total() }}</div>
            <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $teamMembers->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon">⏸️</div>
            <div class="stat-value">{{ $teamMembers->where('status', 'inactive')->count() }}</div>
            <div class="stat-label">Inactive</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon">🆕</div>
            <div class="stat-value">{{ $teamMembers->where('created_at', '>=', now()->subDays(30))->count() }}</div>
            <div class="stat-label">New (30 days)</div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="search-box">
                <input type="text" id="searchMembers" placeholder="Search by name, email, or role..." onkeyup="filterMembers()">
            </div>
            <select class="filter-select" id="statusFilter" onchange="filterMembers()">
                <option value="">All Status</option>
                <option value="active">✅ Active</option>
                <option value="inactive">⏸️ Inactive</option>
            </select>
            <select class="filter-select" id="roleFilter" onchange="filterMembers()">
                <option value="">All Roles</option>
                <option value="developer">Developer</option>
                <option value="designer">Designer</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
        </div>
    </div>

    @if($teamMembers->count() > 0)
        <!-- Team Members Table -->
        <div class="team-table-container">
            <table class="team-table" id="teamMembersTable">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $avatarColors = ['purple', 'blue', 'green', 'orange', 'pink'];
                    @endphp
                    @foreach($teamMembers as $index => $member)
                        @php
                            $avatarColor = $avatarColors[$index % count($avatarColors)];
                            $initials = collect(explode(' ', $member->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
                            $roleClass = strtolower($member->role);
                            if (str_contains($roleClass, 'develop')) $roleClass = 'developer';
                            elseif (str_contains($roleClass, 'design')) $roleClass = 'designer';
                            elseif (str_contains($roleClass, 'manag') || str_contains($roleClass, 'lead')) $roleClass = 'manager';
                            else $roleClass = 'default';
                        @endphp
                        <tr data-name="{{ strtolower($member->name) }}" data-email="{{ strtolower($member->email) }}" data-role="{{ strtolower($member->role) }}" data-status="{{ $member->status }}">
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar {{ $avatarColor }}">{{ $initials }}</div>
                                    <div>
                                        <div class="member-name">{{ $member->name }}</div>
                                        <div class="member-email">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge {{ $roleClass }}">{{ $member->role }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $member->status }}">
                                    <span class="status-dot {{ $member->status }}"></span>
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="join-date">{{ $member->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="/admin/team-members/{{ $member->id }}/edit" class="btn-action edit" title="Edit">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="/admin/team-members/{{ $member->id }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this team member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete">
                                            🗑️ Remove
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($teamMembers->hasPages())
                <div class="pagination-container">
                    {{ $teamMembers->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <h3>No Team Members Yet</h3>
            <p>Start building your team by adding your first member. Team members can be assigned to projects and tasks.</p>
            <a href="/admin/team-members/create" class="btn-add-member">
                <span>➕</span> Add Your First Member
            </a>
        </div>
    @endif
</div>

<script>
    function filterMembers() {
        const searchValue = document.getElementById('searchMembers').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('#teamMembersTable tbody tr');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const role = row.getAttribute('data-role') || '';
            const status = row.getAttribute('data-status') || '';
            
            const matchesSearch = name.includes(searchValue) || email.includes(searchValue) || role.includes(searchValue);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesRole = !roleFilter || role.includes(roleFilter);
            
            row.style.display = matchesSearch && matchesStatus && matchesRole ? '' : 'none';
        });
    }
</script>
@endsection
