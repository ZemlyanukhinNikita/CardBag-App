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

    public function token()
    {
        return $this->hasMany(Token::class);
    }
}