<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $fillable = [
        'name',
        'uid_id',
        'user_id',
        'uid',
        'network_id',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id', 'id');
    }

    public function refreshToken()
    {
        return $this->belongsTo(RefreshToken::class, 'id', 'access_token_id');
    }
}