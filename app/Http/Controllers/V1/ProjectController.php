<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Events\ProjectCreatedEvent;
use App\Models\User;

class ProjectController extends Controller
{
    protected $createInputs = [
        'name' => 'required|string',
    ];

    protected $updateInputs = [
        'name' => 'required|string',
    ];

    public function beforeCreate(Request $request, array $input): array
    {
        $request->validate(['user_id' => 'required|int']);

        $input['enabled'] = true;

        return $input;
    }

    public function findByCriteria(Request $request, string $model): Builder
    {
        $projects = $model::whereRelation('users', 'user_id', $request->user()->id);

        if ($request->input('name')) {
            $projects->where('name', '=', $request->input('name'));
        }

        return $projects;
    }

    public function afterCreate(Request $request, Model $model): void
    {
        $model->users()->attach($request->input('user_id'));
    }
}
