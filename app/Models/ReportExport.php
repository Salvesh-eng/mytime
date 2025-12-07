<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_report_id',
        'format',
        'filename',
        'file_path',
        'exported_by_user_id',
    ];

    /**
     * Get the custom report.
     */
    public function customReport()
    {
        return $this->belongsTo(CustomReport::class);
    }

    /**
     * Get the user who exported the report.
     */
    public function exportedBy()
    {
        return $this->belongsTo(User::class, 'exported_by_user_id');
    }
}
