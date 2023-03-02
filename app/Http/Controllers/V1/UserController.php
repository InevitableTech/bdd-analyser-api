<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Events\UserCreatedEvent;

class UserController extends Controller
{
    protected $createInputs = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'dob' => 'date',
        'email' => 'required|email'
    ];

    protected $updateInputs = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'dob' => 'required|date',
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
        $data = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'enabled' => true,
        ];

        if ($dob = $request->input('dob')) {
            $data['dob'] = new \DateTime($dob);
        }

        return $data;
    }

    public function getId(Request $request, string $email): array
    {
        $user = User::where('email', '=', $email)->firstOrFail();

        return $this->createResponse([['user_id' => $user->id]]);
    }

    protected function afterCreate(Request $request, Model $model): void
    {
        event(new UserCreatedEvent($model));
    }
}
