<?php

class UserProfile
{
    private $fullName;
    private $token;
    private $uid;

    /**
     * UserProfile constructor.
     * @param $fullName
     * @param $token
     * @param $uid
     */
    public function __construct($fullName, $token, $uid)
    {
        $this->fullName = $fullName;
        $this->token = $token;
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }
}