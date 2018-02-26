<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable = ['name'];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}