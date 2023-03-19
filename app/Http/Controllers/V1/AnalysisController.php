<?php

namespace App\Http\Controllers\V1;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AnalysisController extends Controller
{
    public static $createInputs = [
        'user_id' => 'required|int',
        'run_at' => 'required|date',
        'violations' => 'required|json',
        'summary' => 'required|json',
        'active_rules' => 'required|json',
        'active_steps' => 'required|json',
        'rules_version' => 'required|string',
        'severities' => 'required|json',
        'project_id' => 'required|int',
        'branch' => 'nullable|string',
        'commit_hash' => 'nullable|string'
    ];

    public static $updateInputs = [
        'violations_meta' => 'required|json',
    ];

    /**
     * @queryParam project_id number
     * @queryParam main_branch boolean
     */
    protected function findByCriteria(Request $request, string $model): Builder
    {
        // Only that belongs to the user?
        // We also want what belongs to the project that has multiple users.
        $projectId = $request->query('project_id');

        if ($projectId) {
            $project = Project::whereRelation('users', 'user_id', $request->user()->id)
                ->where('id', $projectId)
                ->firstOrFail();

            $query = $model::whereRelation('project', 'project_id', $project->id);

            $mainBranch = $request->query('main_branch');
            if ($mainBranch) {
                $query->where('branch', $project->main_branch);
            }

            return $query;
        }

        return $model::whereRelation('user', 'user_id', $request->user()->id);
    }
}
