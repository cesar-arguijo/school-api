<?php

namespace App\Domain\UserManagement\User\Repository;

use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use App\Domain\UserManagement\User\Entity\User;

/**
 * UserRepositoryInterface
 *
 * Extends the generic `BaseEntityRepositoryInterface` to include user-specific
 * operations, supporting typical CRUD and soft delete functionality, along with
 * specialized methods tailored for the `User` entity.
 *
 * **Purpose**
 *
 * This interface allows for the implementation of user-specific persistence
 * operations, such as retrieving a user by email. It serves as a contract for
 * repositories handling user data within the `UserManagement` domain, enhancing 
 * separation of concerns and facilitating testing through dependency injection.
 *
 * @see BaseEntityRepositoryInterface Provides common repository operations for entities.
 */
interface UserRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * Finds a user by their email address.
     *
     * Retrieves a user entity by their unique email address. This method is
     * particularly useful for login processes, email-based lookups, and scenarios
     * where the email serves as a unique identifier.
     *
     * @param string $email The email address to search for.
     * @return User|null The user entity with the specified email, or null if not found.
     */
    public function findByEmail(string $email): ?User;
}
