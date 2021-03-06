<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = [
        'expires_at',
        'refresh_token',
        'access_token_id'
    ];

    public function accessToken()
    {
        return $this->belongsTo(AccessToken::class, 'access_token_id', 'id');
    }
}