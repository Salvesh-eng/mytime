@extends('layouts.app')

@section('page-title', 'Project Templates')

@section('content')
<style>
    .templates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .template-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-top: 4px solid #2563EB;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .template-card:hover {
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);
        transform: translateY(-2px);
    }

    .template-card h3 {
        margin: 0 0 10px 0;
        font-size: 16px;
        color: #0F172A;
        font-weight: 600;
    }

    .template-card p {
        margin: 0 0 15px 0;
        color: #6B7280;
        font-size: 13px;
        line-height: 1.5;
        flex: 1;
    }

    .template-meta {
        display: flex;
        gap: 15px;
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .template-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .template-actions {
        display: flex;
        gap: 10px;
    }

    .template-actions a, .template-actions button {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s;
    }

    .use-btn {
        background: #2563EB;
        color: white;
    }

    .use-btn:hover {
        background: #1d4ed8;
    }

    .delete-btn {
        background: #f3f4f6;
        color: #6B7280;
    }

    .delete-btn:hover {
        background: #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
    }
</style>

<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 28px; color: #0F172A; font-weight: 700; margin-bottom: 8px;">Project Templates</h1>
        <p style="color: #6B7280; font-size: 14px;">Use templates to quickly create new projects with pre-configured settings</p>
    </div>
    <a href="{{ route('admin.projects.index') }}" style="background: #2563EB; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">← Back to Projects</a>
</div>

@if($templates->count() > 0)
    <div class="templates-grid">
        @foreach($templates as $template)
            <div class="template-card">
                <h3>{{ $template->name }}</h3>
                <p>{{ $template->description ?? 'No description provided' }}</p>

                <div class="template-meta">
                    <span>👥 {{ count($template->team_members ?? []) }} members</span>
                    <span>📊 Used {{ $template->usage_count }} times</span>
                </div>

                <div class="template-actions">
                    <form method="POST" action="{{ route('admin.projects.store') }}" style="flex: 1;">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                        <input type="hidden" name="name" value="New Project from {{ $template->name }}">
                        <input type="hidden" name="start_date" value="{{ now()->format('Y-m-d') }}">
                        <input type="hidden" name="status" value="planning">
                        <button type="submit" class="use-btn" style="width: 100%;">✨ Use Template</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @if($templates->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $templates->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
        <p style="color: #6B7280; font-size: 16px; margin-bottom: 20px;">No templates yet</p>
        <p style="color: #9CA3AF; font-size: 13px; margin-bottom: 20px;">Save any project as a template from the project edit page to get started</p>
        <a href="{{ route('admin.projects.index') }}" style="display: inline-block; background: #2563EB; color: white; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">Create Template from Project</a>
    </div>
@endif

@endsection
