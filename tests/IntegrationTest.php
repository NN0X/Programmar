<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/repositories/UserRepository.php';
require_once __DIR__ . '/../src/Database.php';

class IntegrationTest extends TestCase
{
    private $userRepo;
    private $testEmail = 'phpunit_test@example.com';

    protected function setUp(): void
    {
        $this->userRepo = new UserRepository();

        $existing = $this->userRepo->getUser($this->testEmail);
        if ($existing) {
            $this->userRepo->deleteUser($existing['id']);
        }
    }

    protected function tearDown(): void
    {
        $existing = $this->userRepo->getUser($this->testEmail);
        if ($existing) {
            $this->userRepo->deleteUser($existing['id']);
        }
    }

    public function testUserCanBeCreatedAndRetrieved()
    {
        $password = password_hash('Secret123!', PASSWORD_ARGON2ID);
        $this->userRepo->addUser($this->testEmail, $password);

        $user = $this->userRepo->getUser($this->testEmail);

        $this->assertIsArray($user);
        $this->assertEquals($this->testEmail, $user['email']);
        $this->assertEquals(5, $user['ram']);
    }

    public function testUserRamDeduction()
    {
        $this->userRepo->addUser($this->testEmail, 'pass');
        $user = $this->userRepo->getUser($this->testEmail);

        $newRam = $this->userRepo->deductRam($user['id']);

        $this->assertEquals(4, $newRam);

        $updatedUser = $this->userRepo->getUserById($user['id']);
        $this->assertEquals(4, $updatedUser['ram']);
    }
}
