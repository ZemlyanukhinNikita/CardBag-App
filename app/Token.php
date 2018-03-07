<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['token', 'network_id', 'user_id', 'uid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id', 'id');
    }
}