<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Events\UserCreatedEvent;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public static $createInputs = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'dob' => 'date',
        'email' => 'required|email'
    ];

    public static $updateInputs = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'dob' => 'date',
    ];

    public function auth(Request $request)
    {
        // Auth means we recognise the user, they may not have a token? Issue?
        $request->validate([
            'email' => 'string|required',
            'password' => 'string|required'
        ]);

        $user = User::where('email', $request->input('email'))
            ->where('enabled', 1)
            ->first();

        if ($user && Hash::check($request->input('password'), $user->password_hash)) {
            return $this->getResource($user);
        }

        throw new \Exception('Not found.');
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
