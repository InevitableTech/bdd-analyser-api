<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analysis extends Model
{
    protected $table = 'analysis';

    protected $casts = [
        'outcomes' => 'array',
        'summary' => 'array',
        'active_rules' => 'array',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
