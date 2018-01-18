<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $timestamps = false;

    protected $hidden = ['user_id'];
    
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