<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'entry_date',
        'start_time',
        'end_time',
        'hours',
        'description',
        'status',
        'admin_comment',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'hours' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the time entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the time entry.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Calculate hours from start and end time.
     */
    public static function calculateHours($startTime, $endTime)
    {
        $start = \DateTime::createFromFormat('H:i', $startTime);
        $end = \DateTime::createFromFormat('H:i', $endTime);
        
        if ($end < $start) {
            $end->modify('+1 day');
        }
        
        $interval = $start->diff($end);
        return round($interval->h + ($interval->i / 60), 2);
    }
}
