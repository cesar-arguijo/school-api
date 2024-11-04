<?php

namespace App\Domain\UserManagement\Auth\Service;

use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Entity\Device;
use Symfony\Component\Uid\Uuid;

/**
 * LoginService
 *
 * Manages user login processes, including updating user login timestamps and initiating sessions.
 *
 * **Attributes**
 *
 * - *SessionManager*: Manages user sessions, responsible for creating and tracking active sessions.
 * - *UserManager*: Handles user-related operations, such as updating the last login timestamp.
 *
 * **Workflow**
 *
 * - *Handle Login*:
 *   - Updates the `lastLogin` timestamp for the user.
 *   - Creates a new session associated with the user and device.
 *
 * **Usage Workflow**
 *
 * - `handleUserLogin()` is called when a user attempts to log in, initiating the following actions:
 *   - Updates the `lastLogin` field to reflect the login timestamp.
 *   - Creates a new session with the associated device and user information.
 *
 * @see SessionManagerInterface Provides session management capabilities.
 * @see UserManagerInterface Provides user management operations.
 */
class LoginService
{
    /**
     * @var SessionManagerInterface Manages session-related operations.
     */
    private SessionManagerInterface $sessionManager;

    /**
     * @var UserManagerInterface Manages user-related operations.
     */
    private UserManagerInterface $userManager;

    /**
     * Constructs a new instance of LoginService.
     *
     * @param SessionManagerInterface $sessionManager The session manager to handle session activities.
     * @param UserManagerInterface $userManager The user manager to handle user updates.
     */
    public function __construct(SessionManagerInterface $sessionManager, UserManagerInterface $userManager)
    {
        $this->sessionManager = $sessionManager;
        $this->userManager = $userManager;
    }

    /**
     * Handles user login by updating the last login time and creating a session.
     *
     * - Updates the `lastLogin` timestamp for the specified user.
     * - Initiates a new session linked to the user and device.
     *
     * @param Uuid $userId The unique identifier of the user.
     * @param Uuid $deviceId The unique identifier of the device used for the login.
     * @param \DateTimeImmutable $timestamp The login timestamp.
     * @return void
     */
    public function handleUserLogin(Uuid $userId, Uuid $deviceId, \DateTimeImmutable $timestamp): void
    {
        // Update the user's last login timestamp
        $this->userManager->updateLastLogin($userId, $timestamp);

        // Create a new session for the user
        $this->sessionManager->createSession($userId, $deviceId);
    }
}
