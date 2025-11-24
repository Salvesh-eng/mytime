@extends('layouts.app')

@section('page-title', 'Archived Projects')

@section('content')
<style>
    .archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .archive-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-left: 4px solid #9CA3AF;
        opacity: 0.8;
    }

    .archive-card h3 {
        margin: 0 0 10px 0;
        color: #0F172A;
        font-size: 16px;
    }

    .archive-card p {
        margin: 0 0 12px 0;
        color: #6B7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }

    .action-buttons button, .action-buttons a {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .restore-btn {
        background: #16A34A;
        color: white;
    }

    .restore-btn:hover {
        background: #15803d;
    }

    .delete-btn {
        background: #DC2626;
        color: white;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }
    
    /* Pagination styling */
    .pagination-nav {
        padding: 10px 0;
    }
    
    .pagination-wrapper {
        max-width: 100%;
        overflow-x: auto;
        padding: 5px 0;
    }
    
    /* Hide some pagination elements on smaller screens */
    @media (max-width: 768px) {
        .pagination-nav .hidden.sm\:flex-1 {
            display: none !important;
        }
    }
</style>

<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Archived Projects</h1>
        <p style="color: #6B7280; font-size: 14px;">Restore or permanently delete archived projects</p>
    </div>
    <a href="{{ route('admin.projects.index') }}" style="background: #2563EB; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">← Back to Projects</a>
</div>

@if($projects->count() > 0)
    <div class="archive-grid">
        @foreach($projects as $project)
            <div class="archive-card">
                <h3>{{ $project->name }}</h3>
                <p>{{ Str::limit($project->description, 100) }}</p>
                
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 12px;">
                    <div>📅 Archived: {{ $project->archived_at->format('M d, Y') }}</div>
                    <div>📆 Created: {{ $project->created_at->format('M d, Y') }}</div>
                </div>

                @if($project->client)
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 12px;">
                        👤 {{ $project->client->client_name }}
                    </div>
                @endif

                <div style="display: flex; gap: 8px; font-size: 12px; color: #6B7280; margin-bottom: 12px;">
                    <span>👥 {{ $project->teamMembers->count() }} members</span>
                    @if($project->budget)
                        <span>💰 ${{ number_format($project->budget->spent_amount, 0) }}</span>
                    @endif
                </div>

                <div class="action-buttons">
                    <form method="POST" action="{{ route('admin.projects.restore', $project) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="restore-btn" style="width: 100%;">✓ Restore</button>
                    </form>
                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" style="flex: 1;" onsubmit="return confirm('Permanently delete this project? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" style="width: 100%;">🗑️ Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @if($projects->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $projects->links('vendor.pagination.compact') }}
            </div>
        </div>
    @endif
@else
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; padding: 60px 20px; text-align: center;">
        <div style="font-size: 48px; margin-bottom: 16px;">📦</div>
        <p style="color: #6B7280; font-size: 16px; margin-bottom: 20px;">No archived projects</p>
        <a href="{{ route('admin.projects.index') }}" style="display: inline-block; background: #2563EB; color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">View Active Projects</a>
    </div>
@endif

@endsection
