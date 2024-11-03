<?php

namespace App\Domain\UserManagement\Event;

use App\Domain\Common\Entity\EntityBase;
use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * UserLoggedIn
 *
 * Event triggered when a user logs into the system.
 *
 * **Attributes**
 *
 * - *User ID*: Unique identifier of the user who logged in.
 * - *Session Information*: Details about the session or device used for login.
 * - *Timestamp*: Captures the date and time of the login event.
 *
 * **Event Emission**
 *
 * This event is raised within the `User` entity whenever a user successfully logs in.
 * - The `EventRaiserTrait` collects and manages events, enabling the `User` entity to 
 *   encapsulate domain events, such as login activities.
 *
 * **Usage Workflow**
 *
 * - *Emitting the Event*: The `User` entity triggers this event after a successful login attempt.
 * - *Event Dispatching*: An application service, such as `UserService`, captures and dispatches 
 *   `UserLoggedIn` to the event handler.
 * - *Handling Login Actions*: The `UserLoggedInHandler` receives this event and:
 *   - Updates the user's `lastLogin` timestamp.
 *   - Creates a new session for the user.
 *   - Optionally sends a security alert if a new device is detected.
 *
 * @see EntityBase Base class for entities, providing unique ID and basic entity management.
 */
class UserLoggedIn
{
    /**
     * @var User The user.
     */
    private User $user;

    /**
     * @var Device Information about the user's devise.
     */
    private Device $device;

    /**
     * @var DateTimeImmutable The timestamp of the login.
     */
    private DateTimeImmutable $timestamp;

    /**
     * Initializes a new instance of the UserLoggedIn event.
     *
     * @param User $userId The unique identifier of the logged-in user.
     * @param Device $device Information about the user's device.
     * @param DateTimeImmutable $timestamp The timestamp of the login.
     */
    public function __construct(User $user, Device $device, DateTimeImmutable $timestamp)
    {
        $this->user = $user;
        $this->device = $device;
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the ID of the logged-in user.
     *
     * @return int The user ID.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Gets the device information of the login.
     *
     * @return Device — The device information.
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * Gets the timestamp of the login event.
     *
     * @return DateTimeImmutable The timestamp of the login.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}