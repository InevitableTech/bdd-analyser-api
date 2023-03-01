<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class OrganisationController extends Controller
{
    protected $createInputs = [
        'name' => 'required|string'
    ];

    protected $updateInputs = [
        'name' => 'required|string'
    ];

    protected $expose = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    public function findByCriteria(Request $request, string $model): Builder
    {
        return $model::whereHas('projects', function ($project) use ($request) {
            return $project->whereHas('users', function ($user) use ($request) {
                return $user->where('user_id', $request->user()->id);
            });
        });
    }

    public function mapInputToModel(Request $request): array
    {
        return [
            'name' => $request->input('name')
        ];
    }
}
