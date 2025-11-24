<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TimeEntryController as AdminTimeEntryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TimeEntryController;
use App\Http\Controllers\User\ProfileController;

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    // Time entries
    Route::get('/add-time', [TimeEntryController::class, 'create'])->name('time-entry.create');
    Route::post('/add-time', [TimeEntryController::class, 'store'])->name('time-entry.store');
    Route::get('/my-logs', [TimeEntryController::class, 'index'])->name('time-entry.index');
    Route::get('/my-logs/{entry}', [TimeEntryController::class, 'show'])->name('time-entry.show');
    Route::get('/my-logs/{entry}/edit', [TimeEntryController::class, 'edit'])->name('time-entry.edit');
    Route::put('/my-logs/{entry}', [TimeEntryController::class, 'update'])->name('time-entry.update');
    Route::delete('/my-logs/{entry}', [TimeEntryController::class, 'destroy'])->name('time-entry.destroy');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('admin.notifications.delete');
    
    // User management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('admin.users.activate');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    
    // Time entries
    Route::get('/time-entries', [AdminTimeEntryController::class, 'index'])->name('admin.time-entries.index');
    Route::get('/time-entries/{entry}', [AdminTimeEntryController::class, 'show'])->name('admin.time-entries.show');
    Route::post('/time-entries/{entry}/approve', [AdminTimeEntryController::class, 'approve'])->name('admin.time-entries.approve');
    Route::post('/time-entries/{entry}/reject', [AdminTimeEntryController::class, 'reject'])->name('admin.time-entries.reject');
    Route::post('/time-entries/{entry}/comment', [AdminTimeEntryController::class, 'addComment'])->name('admin.time-entries.comment');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('admin.analytics.export');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('admin.reports.export-csv');
    
    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('admin.projects.index');
    Route::get('/projects/archived', [ProjectController::class, 'archived'])->name('admin.projects.archived');
    Route::get('/projects/templates', [ProjectController::class, 'templates'])->name('admin.projects.templates');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('admin.projects.show');
    Route::post('/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
    Route::post('/projects/{project}/archive', [ProjectController::class, 'archive'])->name('admin.projects.archive');
    Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('admin.projects.restore');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
    Route::post('/projects/{project}/tags', [ProjectController::class, 'updateTags'])->name('admin.projects.update-tags');
    Route::post('/projects/{project}/budget', [ProjectController::class, 'updateBudget'])->name('admin.projects.update-budget');
    Route::post('/projects/{project}/milestone', [ProjectController::class, 'updateMilestone'])->name('admin.projects.update-milestone');
    Route::delete('/projects/milestone/{milestone}', [ProjectController::class, 'destroyMilestone'])->name('admin.projects.destroy-milestone');
    Route::post('/projects/{project}/client', [ProjectController::class, 'updateClient'])->name('admin.projects.update-client');
    Route::post('/projects/{project}/save-template', [ProjectController::class, 'saveAsTemplate'])->name('admin.projects.save-template');
    Route::get('/projects/{project}/analytics', [ProjectController::class, 'getAnalytics']);
    Route::get('/projects/filter/{category}', [ProjectController::class, 'filterByCategory'])->name('admin.projects.filter');
    Route::get('/api/projects/progress-bars', [ProjectController::class, 'getProgressBars']);
    
    // Team Members
    Route::get('/team-members', [TeamMemberController::class, 'index'])->name('admin.team-members.index');
    Route::get('/team-members/create', [TeamMemberController::class, 'create'])->name('admin.team-members.create');
    Route::post('/team-members', [TeamMemberController::class, 'store'])->name('admin.team-members.store');
    Route::get('/team-members/{teamMember}/edit', [TeamMemberController::class, 'edit'])->name('admin.team-members.edit');
    Route::put('/team-members/{teamMember}', [TeamMemberController::class, 'update'])->name('admin.team-members.update');
    Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])->name('admin.team-members.destroy');
});

Route::get('/', function () {
    return redirect('/login');
});

// API Routes
Route::middleware('auth')->group(function () {
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications']);
    Route::get('/api/user/dashboard-metrics', [UserDashboardController::class, 'getMetrics']);
    Route::get('/api/search', [AdminDashboardController::class, 'search']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/admin/dashboard-metrics', [AdminDashboardController::class, 'getMetrics']);
    Route::get('/api/admin/recent-activities', [AdminDashboardController::class, 'getRecentActivities']);
    Route::get('/api/admin/analytics-metrics', [AnalyticsController::class, 'getMetrics'])->name('admin.analytics.getMetrics');
});
