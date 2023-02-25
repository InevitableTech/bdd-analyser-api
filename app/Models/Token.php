<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'expires_on', 'allowed_endpoints', 'user_id'
    ];

    public function project(): BelongsTo
    {
        // select * from `tokens` where exists (select * from `users` inner join `project_user` on
        // `project_user`.`id` = `users`.`project_user_id` where `tokens`.`id` = `project_user`.`token_id` and `user_id` = 1))

        // token <- project_user -> project/user

        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
