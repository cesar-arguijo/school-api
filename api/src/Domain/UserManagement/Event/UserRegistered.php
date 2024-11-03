<?php

namespace App\Domain\UserManagement\Event;

use Symfony\Component\Uid\Uuid;

/**
 * Event triggered when a new user registers in the system.
 * 
 * This event serves multiple purposes, including:
 * - Sending a welcome email to the new user.
 * - Generating an initial session or sending an activation link.
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
     * @param int $userId The ID of the user who registered.
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
     * @return int The user ID.
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
