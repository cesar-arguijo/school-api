<?php

namespace App\Domain\UserManagement\Event;

use App\Domain\UserManagement\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * Event triggered when a user's active session limit is exceeded.
 * 
 * @Attributes
 * - `userId`: The ID of the user.
 * - `limit`: The maximum allowed active sessions.
 * - `timestamp`: When the limit was exceeded.
 * 
 * @Event Emission
 * Emitted when a user attempts to start a session that would exceed the session limit.
 * 
 * @Usage Workflow
 * 1. **Triggered** in `SessionMonitorService` when a user exceeds allowed sessions.
 * 2. **Handled** by a notification service or alert system.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Service\SessionMonitorService
 */
class SessionLimitExceeded
{
    /**
     * The ID of the user who exceeded the session limit.
     *
     * @var User
     */
    private User $user;

    /**
     * The maximum allowed active sessions.
     *
     * @var int
     */
    private int $limit;

    /**
     * Timestamp of when the session limit was exceeded.
     *
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $timestamp;

    /**
     * Constructor.
     *
     * @param User $user The ID of the user.
     * @param int $limit The maximum session limit.
     * @param DateTimeImmutable $timestamp The timestamp of the event.
     */
    public function __construct(User $user, int $limit, DateTimeImmutable $timestamp)
    {
        $this->user = $user;
        $this->limit = $limit;
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
     * Gets the session limit.
     *
     * @return int The session limit.
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Gets the event timestamp.
     *
     * @return DateTimeImmutable The timestamp of when the limit was exceeded.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
