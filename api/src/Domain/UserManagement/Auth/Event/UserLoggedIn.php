<?php

namespace App\Domain\UserManagement\Auth\Event;

use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;

/**
 * UserLoggedIn
 *
 * Event triggered when a user logs into the system.
 *
 * **Attributes**
 *
 * - *User ID*: Unique identifier of the user who logged in.
 * - *Device ID*: Unique identifier of the device used for login.
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
     * @var Uuid Unique identifier of the logged-in user.
     */
    private Uuid $userId;

    /**
     * @var Uuid Unique identifier of the device used for the login.
     */
    private Uuid $deviceId;

    /**
     * @var DateTimeImmutable The timestamp of the login event.
     */
    private DateTimeImmutable $timestamp;

    /**
     * Initializes a new instance of the UserLoggedIn event.
     *
     * @param Uuid $userId The unique identifier of the logged-in user.
     * @param Uuid $deviceId The unique identifier of the device used for the login.
     * @param DateTimeImmutable $timestamp The timestamp of the login.
     */
    public function __construct(Uuid $userId, Uuid $deviceId, DateTimeImmutable $timestamp)
    {
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the unique identifier of the logged-in user.
     *
     * @return Uuid The unique identifier of the user.
     */
    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    /**
     * Gets the unique identifier of the device used for the login.
     *
     * @return Uuid The unique identifier of the device.
     */
    public function getDeviceId(): Uuid
    {
        return $this->deviceId;
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
