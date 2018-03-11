<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = [
        'access_token_id',
        'name',
        'expires_at'
    ];

    public function accessToken()
    {
        return $this->belongsTo(AccessToken::class, 'access_token_id', 'id');
    }

}