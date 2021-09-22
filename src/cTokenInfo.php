<?php

namespace wlhywebservice;

class cTokenInfo
{
    private $Username;
    private $UserTokenID;

    public function __construct($userId, $token)
    {
        $this->Username    = $userId;
        $this->UserTokenID = $token;
    }
}