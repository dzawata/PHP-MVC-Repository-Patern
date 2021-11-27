<?php

namespace Idharf\PhpMvc\Service;

use Idharf\PhpMvc\Config\Database;
use Idharf\PhpMvc\Domain\User;
use Idharf\PhpMvc\Exception\ValidationException;
use Idharf\PhpMvc\Model\UserRegisterRequest;
use Idharf\PhpMvc\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private $userService;
    private $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);
        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id = "rina";
        $request->name = "Rinawati";
        $request->password = "123qweasd";

        $response = $this->userService->register($request);
        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->name, $response->user->name);
        self::assertNotEquals($request->password, $response->user->password);
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";
        $this->userService->register($request);
    }

    public function testRegisterDuplicate()
    {
        $user = new User;
        $user->id = "rina";
        $user->name = "Rinawati";
        $user->password = "123qweasd";
        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $user->id = "rina";
        $user->name = "Rinawati";
        $user->password = "123qweasd";
        $this->userService->register($request);
    }

}