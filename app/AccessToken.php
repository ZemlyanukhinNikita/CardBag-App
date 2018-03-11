<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $fillable = [
        'name',
        'uid_id',
        'user_id',
        'expires_at'
    ];

    public function token()
    {
        return $this->belongsTo(Token::class, 'uid_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}