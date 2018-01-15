<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
