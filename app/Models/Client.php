<?php

namespace App\Models;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'client_id';
    protected $keyType = 'string';
    protected $guarded = [];

    protected $hidden = [
        'client_password'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function username()
    // {
    //     return 'client_username';
    // }

    // public function getAuthUsername() {
    //     return $this->attributes['client_username'];
    // }

    public function getAuthPassword() {
        return $this->client_password;
    }
}
