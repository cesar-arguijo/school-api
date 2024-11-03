<?php

namespace App\Tests\Domain\UserManagement\Entity;

use App\Domain\UserManagement\Entity\Device;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{
    public function testDeviceCreation(): void
    {
        $deviceType = 'mobile';
        $diviceName = 'telecel';
        $device = new Device($deviceType, $diviceName);

        $this->assertEquals($deviceType, $device->getDeviceType());
    }
}
