<?php

namespace App\Domain\UserManagement\Service;

use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Entity\Device;
use App\Domain\UserManagement\Entity\Session;
use App\Domain\UserManagement\Event\SessionStarted;
use App\Domain\UserManagement\Event\UserLoggedIn;
use DateTime;
use DateTimeImmutable;

/**
 * Service to manage user sessions and device verification for security.
 *
 * Attributes:
 * - UserLoggedIn Event: Emits when a user successfully logs in.
 * - Device Verification: Checks if the login device is new.
 *
 * Event Emission:
 * - Each entity (User, Device, Session) manages its events via EventRaiserTrait.
 * - UserLoggedIn is emitted by User to handle post-login processes.
 *
 * Usage Workflow:
 * - A user logs in, triggering Session creation.
 * - The system verifies if the device is new for the user.
 * - User emits the UserLoggedIn event to trigger downstream actions.
 *
 * See Also:
 * - UserLoggedIn: Event triggered after a user login.
 * - Device: Represents user devices in the system.
 * - Session: Handles user sessions and login tracking.
 */
class SessionService
{
    /**
     * Starts a new session for the user, checking device and emitting login events.
     *
     * @param User $user The user logging in.
     * @param Device $device The device from which the user logs in.
     * @return Session The newly created session.
     */
    public function createSession(User $user, Device $device): Session
    {
        $session = new Session(new DateTime(), $device);
        $user->addSession($session);

       // Emitir el evento directamente desde la sesión
        $timestamp = new DateTimeImmutable();
        $session->raiseEvent(new SessionStarted($user, $session, $timestamp));

        return $session;
    }

    /**
     * Determines if the device is new for the user.
     *
     * @param User $user The user initiating the session.
     * @param Device $device The device being used.
     * @return bool True if the device is new for the user; otherwise, false.
     */
    public function isNewDevice(User $user, Device $device): bool
    {
        foreach ($user->getSessions() as $session) {
            if ($session->getDevice()->equals($device)) {
                return false;
            }
        }
        return true;
    }
}
