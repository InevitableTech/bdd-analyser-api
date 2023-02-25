<?php

namespace App\Http\Controllers\V1;

use DateTime;
use Illuminate\Http\Request;
use App\Models\UserProject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    protected $expose = [
        'id',
        'token',
        'user_id',
        'expires_on',
        'created_at'
    ];

    public function findByCriteria(Request $request, string $model): Builder
    {
        $token = $model::whereRelation('user', 'user_id', $request->user()->id);

        if ($request->query('active', null) === '1') {
            $token->where('expires_on', '>', new \DateTime())->first();
        }

        return $token;
    }

    public function mapInputToModel(Request $request): array
    {
        $userId = $request->input('user_id');

        return [
            'token' => Str::random(32),
            'expires_on' => new DateTime('+3 months'),
            'user_id' => $userId,
            'allowed_endpoints' => '*' // This is the token that allows access to all. Probably for the app.
        ];
    }
}
