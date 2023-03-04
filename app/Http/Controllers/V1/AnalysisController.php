<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AnalysisController extends Controller
{
    protected $createInputs = [
        'user_id' => 'required|int',
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
}
