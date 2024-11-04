<?php

namespace App\Domain\UserManagement\User\Service;

use App\Domain\UserManagement\User\Entity\User;
use App\Domain\UserManagement\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * UserService
 *
 * Provides methods for managing general user-related operations, including retrieving
 * user information and updating login timestamps.
 *
 * **Attributes**
 *
 * - *UserRepositoryInterface*: Interface for data access methods related to user entities.
 *
 * **Responsibilities**
 *
 * - Updating the `lastLogin` timestamp when a user logs in.
 * - Retrieving user information by their unique identifier.
 *
 * **Usage Workflow**
 *
 * - `updateLastLogin()`: Called to set the `lastLogin` timestamp for a user, typically
 *   during the login process to track the last login time.
 * - `findUserById()`: Used to fetch user details by their ID, supporting user lookups 
 *   across the application.
 *
 * **Example**
 *
 * ```php
 * $userService->updateLastLogin($userId, new DateTimeImmutable());
 * $user = $userService->findUserById($userId);
 * ```
 *
 * @see UserRepositoryInterface Repository interface for user data access and persistence.
 */
class UserService
{
    private UserRepositoryInterface $userRepository;

    /**
     * Constructs a new instance of UserService.
     *
     * @param UserRepositoryInterface $userRepository The repository interface for managing user data.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Updates the `lastLogin` timestamp for a user.
     *
     * @param Uuid $userId The unique identifier of the user.
     * @param DateTimeImmutable $timestamp The timestamp indicating the user's last login time.
     * @return void
     */
    public function updateLastLogin(Uuid $userId, DateTimeImmutable $timestamp): void
    {
        $user = $this->userRepository->findById($userId);
        if ($user !== null && $user instanceof User) {
            $user->setLastLogin($timestamp);
            $this->userRepository->save($user);
        }
    }

    /**
     * Finds a user by their unique identifier.
     *
     * @param Uuid $userId The unique identifier of the user.
     * @return User|null Returns the user entity if found, otherwise null.
     */
    public function findUserById(Uuid $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
