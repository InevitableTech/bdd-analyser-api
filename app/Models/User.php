<?php

namespace App\Models;

use DateTime;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements AuthenticatableContract, AuthorizableContract
{
    use HasFactory;

    protected $attributes = [
        'enabled' => true,
    ];

    protected $casts = [
        'dob' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'dob', 'enabled', 'password_hash'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'password_hash'
    ];

    public function setDobAttribute(string $value)
    {
        $this->attributes['dob'] = (new DateTime($value))->format('Y-m-d H:i:s');
    }

    public function organisations(): HasManyThrough
    {
        return $this->hasManyThrough(Organisation::class, Project::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function analysis(): HasMany
    {
        return $this->hasMany(Analysis::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
