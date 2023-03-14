<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends BaseResource
{
    protected $expose = [
        'token',
        'expires_on',
        'user_id',
    ];

    protected $relations = [
    ];
}
