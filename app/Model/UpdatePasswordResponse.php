<?php

namespace Idharf\PhpMvc\Model;

use Idharf\PhpMvc\Domain\User;

class UpdatePasswordResponse
{
    public $user;

    public function __construct()
    {
        $this->user = new User();
    }

}