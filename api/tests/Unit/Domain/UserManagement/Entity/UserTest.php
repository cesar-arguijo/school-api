<?php

namespace App\Tests\Domain\UserManagement\Entity;

use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\ValueObject\Password;
use App\Domain\UserManagement\ValueObject\Role;
use App\Domain\UserManagement\Entity\Session;
use PHPUnit\Framework\TestCase;
use DateTime;

class UserTest extends TestCase
{
    private User $user;
    private Password $password;
    private Role $role;

    protected function setUp(): void
    {
        $this->password = new Password('Secure%Password123');
        $this->role = new Role('ROLE_USER');
        $this->user = new User('testuser', 'test@example.com', $this->password, $this->role);
    }

    public function testUserCreation(): void
    {
        $this->assertEquals('testuser', $this->user->getUsername());
        $this->assertEquals('test@example.com', $this->user->getEmail());
        $this->assertSame($this->password, $this->user->getPasswordHash());
        $this->assertSame($this->role, $this->user->getRole());
        $this->assertTrue($this->user->isActive());
    }

    public function testAddAndRemoveSession(): void
    {
        $device = new Device('mobile', 'SamsungS3');
        $session = new Session(new DateTime('+1 hour'), $device);
        $this->user->addSession($session);

        $this->assertCount(1, $this->user->getSessions());
        $this->assertTrue($this->user->getSessions()->contains($session));

        $this->user->removeSession($session);
        $this->assertCount(0, $this->user->getSessions());
    }

    public function testCloseAllSessions(): void
    {
        $mobile = new Device('mobile', 'SamsungS3');
        $desktop = new Device('desktop', 'USERDESKTOP');

        $session1 = new Session(new DateTime('+1 hour'), $mobile);
        $session2 = new Session(new DateTime('+2 hours'), $desktop);

        $this->user->addSession($session1);
        $this->user->addSession($session2);
        
        $this->assertCount(2, $this->user->getSessions());

        $this->user->closeAllSessions();
        $this->assertCount(0, $this->user->getSessions());
    }

     /**
     * Tests the setUsername method to ensure it correctly sets the username.
     *
     * @return void
     */
    public function testSetUsername(): void
    {
        
        // Act: set a new username
        $newUsername = 'newUsername';
        $this->user->setUsername($newUsername);

        // Assert: verify the username was updated
        $this->assertEquals($newUsername, $this->user->getUsername());
    }

    public function testSetLastLogin(): void
    {
        $dateTime = new DateTime();
        $this->user->setLastLogin($dateTime);

        $this->assertSame($dateTime, $this->user->getLastLogin());
    }

    public function testActivateAndDeactivateUser(): void
    {
        $this->user->setActive(false);
        $this->assertFalse($this->user->isActive());

        $this->user->setActive(true);
        $this->assertTrue($this->user->isActive());
    }

    /**
     * Tests the setEmail method to ensure it correctly sets the email address.
     *
     * @return void
     */
    public function testSetEmail(): void
    {
       
        // Act: set a new email address
        $newEmail = 'newemail@example.com';
        $this->user->setEmail($newEmail);

        // Assert: verify the email was updated
        $this->assertEquals($newEmail, $this->user->getEmail());
    }

     /**
     * Tests the setPasswordHash method to ensure it correctly updates the password hash.
     *
     * @return void
     */
    public function testSetPasswordHash(): void
    {
        $newPasswordHash = new Password('Secure%Password2');
        $this->user->setPasswordHash($newPasswordHash);

        // Assert: verify the password hash was updated
        $this->assertSame($newPasswordHash, $this->user->getPasswordHash());
    }

    /**
     * Tests the setRole method to ensure it correctly updates the role.
     *
     * @return void
     */
    public function testSetRole(): void
    {
        
        // Act: set a new role
        $newRole = new Role('ROLE_PRINCIPAL');
        $this->user->setRole($newRole);

        // Assert: verify the role was updated
        $this->assertSame($newRole, $this->user->getRole());
    }
}
