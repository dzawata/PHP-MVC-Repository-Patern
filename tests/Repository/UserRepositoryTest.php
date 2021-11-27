<?php

namespace Idharf\PhpMvc\Repository;

use Idharf\PhpMvc\Config\Database;
use Idharf\PhpMvc\Domain\User;
use PHPUnit\Framework\TestCase;
use Idharf\PhpMvc\Repository\UserRepository;

class UserRepositoryTest extends TestCase
{
    private $userRepository;
    
    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
    }

    public function testSaveSuccess()
    {
        $user = new User;
        $user->id = "ata";
        $user->name = "Dzawata Afnan";
        $user->password = "123qweasd";

        $this->userRepository->save($user);
        $result = $this->userRepository->findById($user->id);
        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById("notfound");
        self::assertNull($user);
    }
}