<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalysisResource extends BaseResource
{
    protected $expose = [
        'id',
        'run_at',
        'rules_version',
        'summary',
        'active_rules',
        'outcomes',
        'project_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
