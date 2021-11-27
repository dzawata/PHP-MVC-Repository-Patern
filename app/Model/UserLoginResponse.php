<?php

namespace Idharf\PhpMvc\Model;

use Idharf\PhpMvc\Domain\User;

class UserLoginResponse
{
    public $user;

    public function __construct()
    {
        $this->user = new User();
    }
}