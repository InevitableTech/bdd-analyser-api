<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analysis extends Model
{
    protected $table = 'analysis';

    protected $casts = [
        'outcomes' => 'array',
        'summary' => 'array',
        'active_rules' => 'array',
        'active_steps' => 'array',
        'violations' => 'array',
        'run_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = [
        'run_at',
        'outcomes',
        'summary',
        'active_rules',
        'rules_version',
        'project_id',
        'user_id'
    ];

    public function setRunAtAttribute(string $value)
    {
        $this->attributes['run_at'] = (new DateTime($value))->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
