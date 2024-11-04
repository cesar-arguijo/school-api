<?php

namespace App\Domain\UserManagement\Auth\Service;

use Symfony\Component\Uid\Uuid;

/**
 * UserManagerInterface
 *
 * Defines the contract for managing user-related actions in the authentication context.
 *
 * This interface provides methods to update user attributes that are essential 
 * for managing authentication, such as the `lastLogin` timestamp, allowing services 
 * to interact with user management operations in a consistent manner.
 *
 * **Responsibilities**
 * - Update user-specific information relevant to authentication activities.
 *
 * **Usage**
 * Implementations of this interface should provide concrete logic for updating
 * the user’s last login time, which is used to track recent user activity.
 */
interface UserManagerInterface
{
    /**
     * Updates the user's last login timestamp.
     *
     * This method is used to set the most recent login time for a user, identified by their unique ID.
     *
     * @param Uuid $user The unique identifier of the user whose login timestamp is being updated.
     * @param \DateTimeImmutable $timestamp The timestamp of the user's last login.
     * @return void
     */
    public function updateLastLogin(Uuid $user, \DateTimeImmutable $timestamp): void;
}
