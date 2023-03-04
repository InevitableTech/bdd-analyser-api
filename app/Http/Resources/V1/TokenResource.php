<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends BaseResource
{
    protected $expose = [
        'id',
        'token',
        'user_id',
        'expires_on',
        'created_at'
    ];
}
