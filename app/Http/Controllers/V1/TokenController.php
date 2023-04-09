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
        'description' => 'nullable|string',
    ];

    public static $updateInputs = [
        'description' => 'nullable|string',
    ];

    protected function beforeCreate(Request $request, array $input): array
    {
        $input['token'] = Crypt::encryptString(Str::random(30));
        $input['expires_on'] = new DateTime('+3 months');
        $input['policies'] = json_encode('*');
        $input['type'] = Token::TYPE_CLI;

        return $input;
    }

    public function findByCriteria(Request $request, string $model): Builder
    {
        $token = $model::whereRelation('user', 'user_id', $request->user()->id);

        if ($request->query('active', null) === '1') {
            $token->where('expires_on', '>', new \DateTime())->first();
        }

        if ($request->query('type')) {
            $token->where('type', $request->query('type'));
        }

        return $token;
    }

    protected function beforeDelete(Request $request, int $id): void
    {
        Token::where('id', $id)->where('type', Token::TYPE_CLI)->firstOrFail();
    }
}
