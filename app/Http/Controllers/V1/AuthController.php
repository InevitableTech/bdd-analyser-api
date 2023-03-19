<?php

namespace App\Http\Controllers\V1;

use DateTime;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public static $createInputs = [
        'email' => 'string|required',
        'password' => 'string|required'
    ];

    public function auth(Request $request)
    {
        // Auth means we recognise the user, they may not have a token? Issue?
        $request->validate([
            'email' => 'string|required',
            'password' => 'string|required'
        ]);

        // Get a user.
        $model = Token::whereRelation('user', 'email', $request->input('email'))
            ->whereRelation('user', 'enabled', 1)
            ->where('type', Token::TYPE_CONSOLE)
            ->first();

        // No, then create and send back.
        if ($model && Hash::check($request->input('password'), $model->user->password_hash)) {
            User::where('id', $model->user_id)->update(['last_login' => (new DateTime())->format('Y-m-d H:i:s')]);

            return $this->getResource($model);
        }

        throw new \Exception('Not found.');
    }
}
