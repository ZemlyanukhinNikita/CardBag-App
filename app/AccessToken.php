<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $fillable = [
        'name',
        'uid_id',
    ];

    public function token()
    {
        return $this->belongsTo(Token::class, 'uid_id', 'id');
    }
}