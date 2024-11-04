<?php

namespace App\Domain\UserManagement\User\Event;

use Symfony\Component\Uid\Uuid;

/**
 * UserRegistered
 *
 * Event triggered when a new user registers in the system.
 *
 * **Attributes**
 *
 * - *User ID*: Unique identifier of the newly registered user.
 * - *Username*: The username chosen by the user during registration.
 * - *Email*: The user's email address, used for communication and activation.
 *
 * **Event Emission**
 *
 * This event is raised by the User entity upon successful registration.
 * 
 * **Usage Workflow**
 * 
 * - Upon user registration, the `UserRegistered` event is emitted to initiate subsequent processes.
 * - Common follow-up actions include:
 *   - Sending a welcome email to the registered email address.
 *   - Generating an initial session or sending an account activation link.
 *
 * **See Also**
 *
 * @see EmailService: For sending the welcome email and activation link.
 * @see UserRegisteredHandler: Handles the event and triggers follow-up processes.
 */
class UserRegistered
{
    /**
     * The unique identifier of the registered user.
     *
     * @var Uuid
     */
    private Uuid $userId;

    /**
     * The username of the registered user.
     *
     * @var string
     */
    private string $username;

    /**
     * The email address of the registered user.
     *
     * @var string
     */
    private string $email;

    /**
     * Initializes a new UserRegistered event instance.
     *
     * @param Uuid $userId The unique identifier of the registered user.
     * @param string $username The username of the registered user.
     * @param string $email The email address of the registered user.
     */
    public function __construct(Uuid $userId, string $username, string $email)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
    }

    /**
     * Gets the unique identifier of the registered user.
     *
     * @return Uuid The user ID.
     */
    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    /**
     * Gets the username of the registered user.
     *
     * @return string The username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Gets the email address of the registered user.
     *
     * @return string The email address.
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
