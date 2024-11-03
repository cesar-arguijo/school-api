<?php

namespace App\Tests\Domain\UserManagement\Entity;

use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Entity\Device;
use PHPUnit\Framework\TestCase;
use DateTime;

class SessionTest extends TestCase
{
    private Session $session;
    private Device $device;

    protected function setUp(): void
    {
        $this->device = new Device('desktop', 'userdesktop');
        $this->session = new Session(new DateTime('+1 hour'), $this->device);
    }

    public function testSessionCreation(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->session->getExpiresAt());
        $this->assertSame($this->device, $this->session->getDevice());
    }

    public function testSessionExpiration(): void
    {
        $expiredSession = new Session(new DateTime('-1 hour'), $this->device);
        $this->assertTrue($expiredSession->isExpired());

        $activeSession = new Session(new DateTime('+1 hour'), $this->device);
        $this->assertFalse($activeSession->isExpired());
    }

    public function testDeviceTypeInSession(): void
    {
        $this->assertEquals('desktop', $this->session->getDevice()->getDeviceType());
    }
}
