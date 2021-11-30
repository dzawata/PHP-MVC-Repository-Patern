<?php

namespace Idharf\PhpMvc\Middleware;

use Idharf\PhpMvc\App\View;
use Idharf\PhpMvc\Config\Database;
use Idharf\PhpMvc\Repository\SessionRepository;
use Idharf\PhpMvc\Repository\UserRepository;
use Idharf\PhpMvc\Service\SessionService;

class MustNotLoginMiddleware implements Middleware
{

    private $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before()
    {
        $user = $this->sessionService->current();
        if($user != null){
            View::redirect('/');
        }
    }
}