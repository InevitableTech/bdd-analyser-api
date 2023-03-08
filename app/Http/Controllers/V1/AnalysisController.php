<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AnalysisController extends Controller
{
    public static $createInputs = [
        'user_id' => 'required|int',
        'run_at' => 'required|date',
        'outcomes' => 'required|json',
        'summary' => 'required|json',
        'active_rules' => 'required|json',
        'rules_version' => 'required',
        'project_id' => 'required|int'
    ];

    protected function findByCriteria(Request $request, string $model): Builder
    {
        return $model::whereRelation('user', 'user_id', $request->user()->id);
    }
}
