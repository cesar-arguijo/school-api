<?php

namespace App\Domain\UserManagement\Service;

use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Event\NewDeviceDetected;
use App\Domain\UserManagement\Event\SessionLimitExceeded;
use App\Infrastructure\Persistence\UserRepository;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * Service for monitoring and managing user sessions.
 *
 * This service is responsible for tracking active sessions, enforcing session limits,
 * and generating alerts when session conditions change, such as new devices or excessive session count.
 * 
 * @Attributes
 * - `userRepository`: Repository for retrieving user and session data.
 * - `sessionLimit`: Maximum number of allowed sessions per user.
 * 
 * @Event Emission
 * This service can raise domain events, such as `SessionLimitExceeded`, when session conditions are met.
 * 
 * @Usage Workflow
 * - **Track Session**: This method registers a new session and performs checks for device changes or session limits.
 * - **Enforce Session Limits**: Verifies the total session count per user.
 * - **Alerting**: When session conditions exceed limits, an alert is raised or notification is sent.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Event\SessionStarted
 * @see App\Domain\UserManagement\EventHandler\SessionStartedHandler
 */
class SessionMonitorService
{
    /**
     * Repository for retrieving user-related data.
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * Maximum allowed sessions per user.
     *
     * @var int
     */
    private int $sessionLimit;

    /**
     * Constructor.
     *
     * @param UserRepository $userRepository The repository for user and session data.
     * @param int $sessionLimit The maximum allowed sessions per user.
     */
    public function __construct(UserRepository $userRepository, int $sessionLimit = 5)
    {
        $this->userRepository = $userRepository;
        $this->sessionLimit = $sessionLimit;
    }

    /**
     * Tracks a new session for a user, checking device and session limit conditions.
     *
     * @param User $user The ID of the user.
     * @param session $session The type of device (e.g., mobile, desktop).
     * @param DateTimeImmutable $timestamp The timestamp of the session start.
     * @return void
     */
    public function trackSession(
        User $user, 
        Session $session, 
        DateTimeImmutable $timestamp
    ): void
    {

        $activeSessions = $user->getSessions()->filter(fn(Session $session) => !$session->isExpired());

        // Enforce session limit
        if ($activeSessions->count() >= $this->sessionLimit) {
            // Raise an event or send notification for session limit exceeded
            $user->raiseEvent(new SessionLimitExceeded($user, $this->sessionLimit, $timestamp));
        }

        // Check for dsession;ange and raise an event if a new device is detected
        if (!$this->isKnownDevice($user, $session)) {
            $user->raiseEvent(new NewDeviceDetected($user, $session->getDevice(), $timestamp));
        }
    }

    /**
     * Checks if the user has previously logged in with the specified device type.
     *
     * @param User $user The user entity.
     * @param Device $deviceType The type of device.
     * @return bool True if the device type is known, false otherwise.
     */
    private function isKnownDevice(User $user, Session $session): bool
    {
        foreach ($user->getSessions() as $otherSession) {
            if (
                !$session->equals($otherSession) &&
                $otherSession->getDevice()->equals($session->getDevice()) && 
                !$otherSession->isExpired()
            ) {
                return true;
            }
        }
        return false;
    }
}
