<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['token', 'network_id', 'user_id', 'uid'];
}