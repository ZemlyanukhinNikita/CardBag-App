<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UserNetwork extends Model
{
    protected $table = 'user_networks';

    protected $fillable = [
        'user_identity',
        'user_id',
        'network_id'
    ];

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}