<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class User extends Model
{
    private $id;
    private $fullname;
    private $token;
    private $uid;

    protected $fillable = ['full_name'];

    protected $hidden = ['tokenName'];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullName($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

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