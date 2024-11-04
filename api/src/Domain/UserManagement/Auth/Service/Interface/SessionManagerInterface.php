<?php

namespace App\Domain\UserManagement\Auth\Service\Interface;

use Symfony\Component\Uid\Uuid;

/**
 * SessionManagerInterface
 *
 * Defines the contract for managing user sessions within the authentication domain.
 *
 * **Attributes**
 *
 * - *Session Creation*: Enables the creation of a session associated with a user and device.
 *
 * **Usage Workflow**
 *
 * - *Session Initialization*: The `createSession()` method is called to initiate a new session for a specified user and device.
 *   - This method is typically invoked within login workflows, where a session needs to be created after a user logs in.
 *
 * **Responsibilities**
 *
 * - Establishes a session link between a user and their device upon login.
 * - Supports session lifecycle management within the domain, ensuring each session is properly initiated and tracked.
 *
 * @see LoginService Handles user login and calls `SessionManagerInterface` for session creation.
 */
interface SessionManagerInterface
{
    /**
     * Creates a new session for a user associated with a specific device.
     *
     * @param Uuid $user The unique identifier of the user.
     * @param Uuid $device The unique identifier of the device used for the session.
     * @return void
     */
    public function createSession(Uuid $user, Uuid $device): void;
}
