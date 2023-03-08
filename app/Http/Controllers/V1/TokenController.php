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
    public static $createInputs = [
        'user_id' => 'required|int',
    ];

    public static $updateInputs = [
        'token' => 'required|string',
    ];

    protected function beforeCreate(Request $request, array $input): array
    {
        $input['token'] = Str::random(60);
        $input['expires_on'] = new DateTime('+3 months');
        $input['allowed_endpoints'] = '*';

        return $input;
    }

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
}
