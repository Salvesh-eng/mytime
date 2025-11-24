<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeEntryController extends Controller
{
    /**
     * Show all time entries.
     */
    public function index(Request $request)
    {
        $query = TimeEntry::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('entry_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('entry_date', '<=', $request->date_to);
        }

        $entries = $query->latest('entry_date')->paginate(20);
        $users = User::where('role', 'user')->get();

        return view('admin.time-entries.index', [
            'entries' => $entries,
            'users' => $users,
        ]);
    }

    /**
     * Show time entry details.
     */
    public function show(TimeEntry $entry)
    {
        return view('admin.time-entries.show', ['entry' => $entry]);
    }

    /**
     * Approve time entry.
     */
    public function approve(TimeEntry $entry)
    {
        $entry->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Time entry approved');
    }

    /**
     * Reject time entry.
     */
    public function reject(Request $request, TimeEntry $entry)
    {
        $validated = $request->validate([
            'admin_comment' => 'required|string|max:500',
        ]);

        $entry->update([
            'status' => 'rejected',
            'admin_comment' => $validated['admin_comment'],
        ]);

        return redirect()->back()->with('success', 'Time entry rejected');
    }

    /**
     * Add comment to time entry.
     */
    public function addComment(Request $request, TimeEntry $entry)
    {
        $validated = $request->validate([
            'admin_comment' => 'required|string|max:500',
        ]);

        $entry->update(['admin_comment' => $validated['admin_comment']]);
        return redirect()->back()->with('success', 'Comment added');
    }
}
