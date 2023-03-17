<?php

namespace App\Http\Controllers\V1;

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

    protected function findByCriteria(Request $request, string $model): Builder
    {
        return $model::whereRelation('user', 'user_id', $request->user()->id);
    }
}
