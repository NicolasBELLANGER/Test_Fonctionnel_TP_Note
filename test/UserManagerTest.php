<?php

use PHPUnit\Framework\TestCase;
use Nicolas\TestUnitaires\UserManager;

class UserManagerTest extends TestCase
{
    private UserManager $userManager;

    protected function setUp(): void
    { {
            $this->userManager = new UserManager();
            $pdo = new PDO("mysql:host=localhost;dbname=user_management;charset=utf8", "root", "");
            $pdo->exec("DELETE FROM users");
        }
    }

    public function testAddUser(): void
    {
        $this->userManager->addUser('Nicolas', 'nicolas@example.com');
        $users = $this->userManager->getUsers();
        $this->assertCount(1, $users);
        $this->assertEquals('Nicolas', $users[0]['name']);
    }

    public function testAddUserInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->userManager->addUser('Nicolas', 'nicolasexamplecom');
    }

    public function testGetUser(): void
    {
        $this->userManager->addUser('Nicolas', 'nicolas@example.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id'];

        $user = $this->userManager->getUser($userId);
        $this->assertEquals('Nicolas', $user['name']);
    }

    public function testRemoveUser(): void
    {
        $this->userManager->addUser('Nicolas', 'nicolas@example.com');
        $users = $this->userManager->getUsers();
        $id = $users[0]['id'];

        $this->userManager->removeUser($id);
        $this->assertCount(0, $this->userManager->getUsers());
    }

    public function testUpdateUser(): void
    {
        $this->userManager->addUser('Nicolas', 'nicolas@example.com');
        $users = $this->userManager->getUsers();
        $id = $users[0]['id'];

        $this->userManager->updateUser($id, 'Nico', 'nico@example.com');
        $updated = $this->userManager->getUser($id);

        $this->assertEquals('Nico', $updated['name']);
        $this->assertEquals('nico@example.com', $updated['email']);
    }

    public function testInvalidUpdateThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Impossible de mettre à jour l'utilisateur. L'ID 100 n'existe pas.");

        $this->userManager->updateUser(100, 'Test', 'test@example.com');
    }

    public function testInvalidDeleteThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Impossible de supprimer l'utilisateur. L'ID 100 n'existe pas.");

        $this->userManager->removeUser(100);
    }
}
