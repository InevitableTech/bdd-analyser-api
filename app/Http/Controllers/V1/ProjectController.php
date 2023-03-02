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
        'user_id' => 'required|int',
    ];

    protected $updateInputs = [
        'name' => 'required|string',
    ];

    public function mapInputToModel(Request $request): array
    {
        return [
            'name' => $request->input('name'),
            'enabled' => true
        ];
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
        event(new ProjectCreatedEvent($model, User::find($request->input('user_id'))));
    }
}
