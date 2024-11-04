<?php

namespace App\Domain\UserManagement\Event;

use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * Event triggered when a user logs in from a new device.
 * 
 * @Attributes
 * - `userId`: The ID of the user.
 * - `deviceType`: Type of the new device detected.
 * - `timestamp`: When the device was detected.
 * 
 * @Event Emission
 * Emitted when a user logs in from a previously unused device.
 * 
 * @Usage Workflow
 * 1. **Triggered** in `SessionMonitorService` upon detecting a new device.
 * 2. **Handled** by a notification service to alert the user of a new device login.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Service\SessionMonitorService
 */
class NewDeviceDetected
{
    /**
     * The ID of the user who logged in from a new device.
     *
     * @var User
     */
    private User $user;

    /**
     * Type of the new device detected (e.g., mobile, desktop).
     *
     * @var Device
     */
    private Device $device;

    /**
     * Timestamp of the device detection event.
     *
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $timestamp;

    /**
     * Constructor.
     *
     * @param User $user The ID of the user.
     * @param Device $device The type of the detected device.
     * @param DateTimeImmutable $timestamp The timestamp of the event.
     */
    public function __construct(User $user, Device $device, DateTimeImmutable $timestamp)
    {
        $this->user = $user;
        $this->device = $device;
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the user ID.
     *
     * @return Uuid The user ID.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Gets the device type.
     *
     * @return Device The detected device type.
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * Gets the event timestamp.
     *
     * @return DateTimeImmutable The timestamp of the device detection.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
