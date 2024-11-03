<?php

namespace App\Domain\UserManagement\Event;

use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event emitted when a session ends.
 * 
 * This event captures essential details when a session is ended, including
 * the user ID, session ID, and timestamp of the event. It is useful for
 * resource cleanup, session auditing, and managing session lifecycles.
 * 
 * @Attributes
 * - `userId` (Uuid): The unique identifier of the user whose session ended.
 * - `sessionId` (Uuid): The unique identifier of the ended session.
 * - `timestamp` (DateTimeImmutable): The timestamp when the session ended.
 * 
 * @Event Emission
 * This event is raised by `SessionService` after a session is terminated.
 * 
 * @Usage Workflow
 * The event is emitted by `SessionService` to indicate the end of a session. 
 * It is dispatched to notify relevant services to clean up resources, 
 * audit session duration, or execute additional actions based on session termination.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Service\SessionService
 * @see App\Domain\UserManagement\EventHandler\SessionEndedHandler
 */
class SessionEnded extends Event
{
    private User $user;
    private Session $session;
    private DateTimeImmutable $timestamp;

    /**
     * Constructor.
     * 
     * @param User $userId The ID of the user.
     * @param Session $sessionId The ID of the ended session.
     * @param DateTimeImmutable $timestamp The timestamp of session termination.
     */
    public function __construct(User $user, Session $session, DateTimeImmutable $timestamp)
    {
        $this->user = $user;
        $this->session = $session;
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the user ID.
     * 
     * @return User The unique identifier of the user.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Gets the session ID.
     * 
     * @return Session The unique identifier of the session.
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Gets the timestamp of session termination.
     * 
     * @return DateTimeImmutable The timestamp when the session ended.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
