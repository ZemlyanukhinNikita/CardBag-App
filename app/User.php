<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class User extends Model
{
    protected $fillable = ['full_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function photos()
    {
        return $this->hasMany(Card::class);
    }

    public function accessTokens()
    {
        return $this->hasMany(AccessToken::class, 'id', 'user_id');
    }

    public function userData()
    {
        return $this->belongsTo(UserNetwork::class, 'id', 'user_id');
    }

}