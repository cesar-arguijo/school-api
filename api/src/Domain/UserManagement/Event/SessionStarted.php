<?php

namespace App\Domain\UserManagement\Event;

use DateTimeImmutable;
use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Entity\User;
use Symfony\Component\Uid\Uuid;

/**
 * Event triggered when a new user session begins.
 * 
 * This event captures details about the user, device, and session start time.
 *
 * ## Attributes
 * - **User ID**: Unique identifier for the user starting a new session.
 * - **Device**: The device used during the session start.
 * - **Device Type**: The type of device (e.g., "mobile", "desktop").
 * - **Timestamp**: Time when the session started, used for tracking and auditing.
 * 
 * ## Event Emission
 * Emitted by the `Session` entity via `EventRaiserTrait` when a session starts.
 * 
 * ## Usage Workflow
 * 1. A new session is created for the user.
 * 2. The `Session` entity emits the `SessionStarted` event.
 * 3. The event is dispatched, activating relevant listeners and handlers.
 * 
 * ## See Also
 * - [User](../Entity/User.php)
 * - [Device](../Entity/Device.php)
 * - [Session](../Entity/Session.php)
 */
class SessionStarted
{
    /**
     * @var User Unique identifier of the user starting the session.
     */
    private User $user;

    /**
     * @var Session The device used for the session.
     */
    private Session $session;

    /**
     * @var DateTimeImmutable Timestamp for the session start.
     */
    private DateTimeImmutable $timestamp;

    /**
     * Constructs the SessionStarted event.
     *
     * @param User $userId The ID of the user starting the session.
     * @param Session $session The device information for the session.
     * @param DateTimeImmutable $timestamp The time the session started.
     */
    public function __construct(User $user, Session $session, DateTimeImmutable $timestamp)
    {
        $this->user = $user;
        $this->session = $session;
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the user ID of the user starting the session.
     *
     * @return User The user's unique identifier.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Gets the device used for the session.
     *
     * @return Session The device entity.
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Gets the type of device for the session.
     *
     * @return string The device type.
     */
    public function getDeviceType(): string
    {
        return $this->session->getDevice()->getDeviceType();
    }

    /**
     * Gets the timestamp of the session start.
     *
     * @return DateTimeImmutable The timestamp of the session start.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
