<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class OrganisationController extends Controller
{
    public static $createInputs = [
        'name' => 'required|string'
    ];

    public static $updateInputs = [
        'name' => 'required|string'
    ];

    public function findByCriteria(Request $request, string $model): Builder
    {
        return $model::whereHas(
            'projects',
            fn ($project) => $project
            ->whereHas(
                'users',
                fn ($user) => $user
                ->where('user_id', $request->user()->id)
            )
        );
    }
}
