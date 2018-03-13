<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = [
        'user_id',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}