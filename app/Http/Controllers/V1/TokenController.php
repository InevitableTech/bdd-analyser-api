<?php

namespace App\Http\Controllers\V1;

use DateTime;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Resources\TokenResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class TokenController extends Controller
{
    protected $createInputs = [
        'user_id' => 'required|int',
    ];

    protected $updateInputs = [
        'token' => 'required|string',
    ];

    public function create(Request $request): JsonResource
    {
        // Create only if we've not got an active one already.
        $token = Token::whereRelation('user', 'user_id', $request->input('user_id'))
            ->where('expires_on', '>', new \DateTime())->first();

        if (! $token) {
            return parent::create($request);
        }

        return $this->getResource($token);
    }

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
            // Token needs to be saved encrypted.
            'token' => Str::random(60),
            'expires_on' => new DateTime('+3 months'),
            'user_id' => $userId,
            'allowed_endpoints' => '*' // This is the token that allows access to all. Probably for the app.
        ];
    }

    public function refresh()
    {
        // Expire the old one.
        // Create a new one fresh one and return.
        // To call refresh, you must have an expired token? along with user_id?
    }
}
