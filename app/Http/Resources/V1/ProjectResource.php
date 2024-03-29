<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends BaseResource
{
    protected $expose = [
        'id',
        'name',
        'repo_url',
        'main_branch',
        'published_tags',
        'resolutions',
        'created_at',
        'updated_at',
        'enabled',
    ];

    protected $relations = [
        'analysis' => ['id', 'run_at'],
        'organisation' => ['id', 'name'],
    ];
}
