<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Events\UserCreatedEvent;

class UserController extends Controller
{
    protected $validateInputs = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'dob' => 'required|date',
        'email' => 'required|email'
    ];

    protected $expose = [
        'id',
        'firstname',
        'lastname',
        'dob',
        'email',
        'created_at',
        'updated_at',
        'enabled'
    ];

    public function findByCriteria(Request $request, string $model): ?Builder
    {
        if ($request->input('email')) {
            return $model::where('email', '=', $request->input('email'));
        }

        if ($request->user()) {
            return $model::where('id', '=', $request->user()->id);
        }

        return null;
    }

    public function mapInputToModel(Request $request): array
    {
        return [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'dob' => new \DateTime($request->input('dob')),
            'email' => $request->input('email'),
            'enabled' => true,
        ];
    }

    protected function afterCreate(Request $request, Model $model): void
    {
        event(new UserCreatedEvent($model));
    }
}
