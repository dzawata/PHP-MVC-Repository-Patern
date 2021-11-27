<?php

namespace Idharf\PhpMvc\Middleware;

class AuthMiddlewareImpl implements Middleware{

    function before()
    {
        session_start();
        if(!isset($_SESSION['user'])){
            header('Location:/');
            exit();
        }
    }
}