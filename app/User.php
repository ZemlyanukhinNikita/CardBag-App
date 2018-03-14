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

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class,'id', 'user_id');
    }

    public function networks()
    {
        return $this->hasMany(Network::class,'network_id', 'id');
    }
}