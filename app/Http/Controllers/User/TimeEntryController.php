<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeEntryController extends Controller
{
    /**
     * Show add time entry form.
     */
    public function create()
    {
        return view('user.time-entries.create');
    }

    /**
     * Store time entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date|before_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:500',
        ]);

        $hours = TimeEntry::calculateHours($validated['start_time'], $validated['end_time']);

        TimeEntry::create([
            'user_id' => auth()->id(),
            'entry_date' => $validated['entry_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'hours' => $hours,
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect('/my-logs')->with('success', 'Time entry added successfully');
    }

    /**
     * Show all user's time logs.
     */
    public function index(Request $request)
    {
        $query = TimeEntry::where('user_id', auth()->id());

        if ($request->filled('date_from')) {
            $query->whereDate('entry_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('entry_date', '<=', $request->date_to);
        }

        $entries = $query->latest('entry_date')->paginate(15);

        return view('user.time-entries.index', ['entries' => $entries]);
    }

    /**
     * Show time entry details.
     */
    public function show(TimeEntry $entry)
    {
        if ($entry->user_id !== auth()->id()) {
            return redirect('/my-logs')->with('error', 'Unauthorized');
        }
        return view('user.time-entries.show', ['entry' => $entry]);
    }

    /**
     * Show edit form.
     */
    public function edit(TimeEntry $entry)
    {
        if ($entry->user_id !== auth()->id() || $entry->status !== 'pending') {
            return redirect('/my-logs')->with('error', 'Cannot edit this entry');
        }
        return view('user.time-entries.edit', ['entry' => $entry]);
    }

    /**
     * Update time entry.
     */
    public function update(Request $request, TimeEntry $entry)
    {
        if ($entry->user_id !== auth()->id() || $entry->status !== 'pending') {
            return redirect('/my-logs')->with('error', 'Cannot edit this entry');
        }

        $validated = $request->validate([
            'entry_date' => 'required|date|before_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:500',
        ]);

        $hours = TimeEntry::calculateHours($validated['start_time'], $validated['end_time']);

        $entry->update([
            'entry_date' => $validated['entry_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'hours' => $hours,
            'description' => $validated['description'],
        ]);

        return redirect('/my-logs')->with('success', 'Time entry updated successfully');
    }

    /**
     * Delete time entry.
     */
    public function destroy(TimeEntry $entry)
    {
        if ($entry->user_id !== auth()->id() || $entry->status !== 'pending') {
            return redirect('/my-logs')->with('error', 'Cannot delete this entry');
        }

        $entry->delete();
        return redirect('/my-logs')->with('success', 'Time entry deleted');
    }
}
