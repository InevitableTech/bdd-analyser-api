<?php

namespace App\Http\Controllers\V1;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Events\UserCreatedEvent;

class UserController extends Controller
{
    public static $createInputs = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'dob' => 'nullable|date',
        'email' => 'required|email',
        'password' => 'required|string|min:8'
    ];

    public static $updateInputs = [
        'firstname' => 'string',
        'lastname' => 'string',
        'dob' => 'nullable|date',
        'password' => 'required|string|min:8'
    ];

    protected function beforeCreate(Request $request, array $inputs): array
    {
        $user = User::where('email', $inputs['email'])->first();
        if ($user) {
            throw new Exception('A user with this email exists.');
        }

        $inputs['password_hash'] = Hash::make($inputs['password']);
        unset($inputs['password']);

        return $inputs;
    }

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

    public function getId(Request $request, string $email): array
    {
        $user = User::where('email', '=', $email)->firstOrFail();

        return $this->createResponse([['user_id' => $user->id]]);
    }

    public function afterCreate(Request $request, Model $model): void
    {
        UserCreatedEvent::dispatch($model);
    }
}
