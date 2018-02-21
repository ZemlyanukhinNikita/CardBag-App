<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class User extends Model
{
    protected $fillable = ['full_name', 'uid', 'token'];

    protected $hidden = ['tokenName'];

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

    public function tokenName()
    {
        return $this->belongsTo(Token::class, 'token', 'id');
    }
}