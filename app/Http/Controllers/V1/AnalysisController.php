<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AnalysisController extends Controller
{
    protected $createInputs = [
        'run_at' => 'required|string',
        'outcomes' => 'required',
        'summary' => 'required',
        'active_rules' => 'required',
        'rules_version' => 'required',
        'project_id' => 'required|int'
    ];

    protected $updateInputs = false;

    protected function findByCriteria(Request $request, string $model): Builder
    {
        return $model::whereRelation('user', 'user_id', $request->user()->id);
    }

    protected function mapInputToModel(Request $request): array
    {
        return [
            'run_at' => $request->input('run_at'),
            'outcomes' => $request->input('outcomes'),
            'summary' => $request->input('summary'),
            'active_rules' => $request->input('active_rules'),
            'rules_version' => $request->input('rules_version'),
            'project_id' => $request->input('project_id'),
            'user_id' => $request->user()->id,
        ];
    }
}
