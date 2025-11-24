<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTag extends Model
{
    protected $fillable = [
        'project_id',
        'category',
        'color',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function getCategoryColors()
    {
        return [
            'Development' => '#3B82F6',
            'Marketing' => '#EC4899',
            'Design' => '#8B5CF6',
            'Infrastructure' => '#F59E0B',
            'Testing' => '#10B981',
            'Documentation' => '#6B7280',
            'Other' => '#2563EB',
        ];
    }

    public function getColorAttribute()
    {
        if ($this->attributes['color'] ?? null) {
            return $this->attributes['color'];
        }

        $colors = self::getCategoryColors();
        return $colors[$this->category] ?? '#2563EB';
    }
}
