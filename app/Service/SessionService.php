<?php

namespace Idharf\PhpMvc\Service;

use Idharf\PhpMvc\Domain\Session;
use Idharf\PhpMvc\Repository\SessionRepository;
use Idharf\PhpMvc\Repository\UserRepository;

class SessionService
{
    public static $COOKIE_NAME = "X-SESSION";
    private $sessionRepository;
    private $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create($userId)
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);
        setcookie(self::$COOKIE_NAME,$session->id,time()+(60),'/');
        return $session;

    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);
        setcookie(self::$COOKIE_NAME,'',1,'/');
    }

    public function current()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->findById($sessionId);
        if($session == null){
            return null;
        }
        return $this->userRepository->findById($session->userId);
    }

}