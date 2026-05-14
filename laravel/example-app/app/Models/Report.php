<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'credits_used',
        'sections',
        'selected_sections',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'credits_used' => 'integer',
            'sections' => 'array',
            'selected_sections' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
