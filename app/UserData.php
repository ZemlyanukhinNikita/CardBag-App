<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable = [
        'uid',
        'user_id',
        'network_id'
    ];

    public function network()
    {
        return $this->hasMany(Network::class,'network_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}