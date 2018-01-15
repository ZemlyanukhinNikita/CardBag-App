<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function categories()
    {
        {
            return $this->belongsTo(Category::class);
        }
    }

    public function users()
    {
        {
            return $this->belongsTo(User::class);
        }
    }
}
