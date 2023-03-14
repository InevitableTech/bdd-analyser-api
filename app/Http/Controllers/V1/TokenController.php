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
        $input['token'] = Crypt::encryptString(Str::random(60));
        $input['expires_on'] = new DateTime('+3 months');
        $input['allowed_endpoints'] = json_encode(['policies' => '*']);

        return $input;
    }

    public function create(Request $request): JsonResource
    {
        // They must login to the console, generate a token and then register it with the CLI tool.
        // Its the only way.
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

    protected function afterFind(Request $request, Model $model): void
    {
        $model->token = Crypt::decryptString($model->token);
    }
}
