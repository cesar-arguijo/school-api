<?php

namespace App\Domain\UserManagement\Event;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * Event triggered when a user changes their password.
 *
 * @package App\Domain\UserManagement\Event
 *
 * ## Attributes
 * - **userId**: ID of the user changing their password.
 * - **timestamp**: The date and time when the password was changed.
 *
 * ## Event Emission
 * This event is raised in the `User` entity through the `EventRaiserTrait` when a password change occurs.
 * 
 * ## Usage Workflow
 * 1. **Event Emission**: The `User` entity triggers `PasswordChanged` upon password update.
 * 2. **Event Dispatching**: `UserService` dispatches this event using `EventDispatcherInterface` after updating the password.
 * 3. **Handling**: `PasswordChangedHandler` listens for `PasswordChanged` and performs actions, such as:
 *     - Logging the password change.
 *     - Sending a notification to the user.
 *
 * ## See Also
 * @see UserService for event dispatching.
 * @see User for the entity emitting the event.
 */
class PasswordChanged
{
    /**
     * @var Uuid The unique identifier of the user who changed their password.
     */
    private Uuid $userId;

    /**
     * @var DateTimeImmutable The timestamp of when the password change occurred.
     */
    private DateTimeImmutable $timestamp;

    /**
     * Constructs a new PasswordChanged event.
     *
     * @param Uuid $userId The ID of the user changing their password.
     * @param DateTimeImmutable $timestamp The time of the password change.
     */
    public function __construct(Uuid $userId, DateTimeImmutable $timestamp)
    {
        $this->userId = $userId;
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the user ID associated with this event.
     *
     * @return Uuid The user's unique identifier.
     */
    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    /**
     * Gets the timestamp of when the password change occurred.
     *
     * @return DateTimeImmutable The event timestamp.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
