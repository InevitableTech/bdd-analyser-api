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
        'active_steps',
        'violations',
        'violations_meta',
        'branch',
        'commit_hash',
        'project_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $relations = [
        'user' => ['id', 'firstname'],
        'project' => ['id', 'name']
    ];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = $this->expose($this->resource);

        $array['summary'] = json_decode($this->resource->summary, true);
        $array['violations'] = json_decode($this->resource->violations, true);
        $array['active_steps'] = json_decode($this->resource->active_steps, true);
        $array['active_rules'] = json_decode($this->resource->active_rules, true);
        $array['severities'] = json_decode($this->resource->severities, true);

        return $array;
    }
}
