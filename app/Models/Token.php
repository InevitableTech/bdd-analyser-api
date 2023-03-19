<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    const TYPE_CONSOLE = 'console';

    const TYPE_CLI = 'cli';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'expires_on', 'allowed_endpoints', 'user_id', 'type', 'policies', 'description'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
