<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}