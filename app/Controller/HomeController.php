<?php

namespace Idharf\PhpMvc\Controller;

use Idharf\PhpMvc\App\View;
use Idharf\PhpMvc\Config\Database;
use Idharf\PhpMvc\Repository\SessionRepository;
use Idharf\PhpMvc\Repository\UserRepository;
use Idharf\PhpMvc\Service\SessionService;

class HomeController
{
    
    private $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }
    
    public function index()
    {
        $user = $this->sessionService->current();
        if($user == null){
            $location = "Home/index";
            $model = array(
                'title' => 'PHP MVC',
            );
        }else{
            $location = "Home/dashboard";
            $model = array(
                'title' => 'Dashboard | ' . $user->name,
                'name' => $user->name
            );
        }

        View::render($location, $model);
    }

    public function hallo()
    {
        echo "HomeController.hallo()";
    }

    public function hey()
    {
        echo "HomeController.hey()";
    }
}