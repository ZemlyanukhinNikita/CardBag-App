<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    protected $fillable = ['user_id', 'filename'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
