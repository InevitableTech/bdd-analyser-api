<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends BaseResource
{
    protected $expose = [
        'id',
        'firstname',
        'lastname',
        'dob',
        'email',
        'last_login',
        'verified',
        'created_at',
        'updated_at',
        'enabled'
    ];

    protected $relations = [
        'projects' => ['project_id', 'name'],
        'analysis' => ['analysis.id', 'run_at']
    ];
}
