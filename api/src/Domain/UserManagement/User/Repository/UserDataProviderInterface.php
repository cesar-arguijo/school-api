<?php

namespace App\Domain\UserManagement\User\DataProvider;

use Symfony\Component\Uid\Uuid;
use App\Domain\UserManagement\User\Entity\User;

/**
 * UserDataProviderInterface
 *
 * Provides read-only methods to retrieve user data without exposing any modification capabilities.
 * This interface is intended for use in scenarios where only user data fetching is required, 
 * promoting a clean separation of concerns within the application.
 *
 * **Usage**
 *
 * This interface can be implemented by services that are responsible for fetching user information
 * based on various identifiers, such as user ID or email. It is particularly useful in contexts
 * where read-only access to user data is needed.
 */
interface UserDataProviderInterface
{
    /**
     * Finds a user by their unique ID.
     *
     * This method retrieves a user entity using its unique identifier.
     *
     * @param Uuid $id The unique identifier of the user.
     * @return User|null Returns the user entity if found, or null if no user with the given ID exists.
     */
    public function findById(Uuid $id): ?User;

    /**
     * Finds a user by their email address.
     *
     * Retrieves a user entity using their email, facilitating lookups based on unique email identifiers.
     *
     * @param string $email The email address of the user.
     * @return User|null Returns the user entity if found, or null if no user with the given email exists.
     */
    public function findByEmail(string $email): ?User;
}
