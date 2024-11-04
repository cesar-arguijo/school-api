<?php

namespace App\Infrastructure\Adapter;

use App\Domain\UserManagement\Auth\Service\UserManagerInterface;
use App\Infrastructure\Persistence\UserRepository;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;

/**
 * UserManagerAdapter
 *
 * Adapter for managing user data, interacting with UserRepository.
 */
class UserManagerAdapter implements UserManagerInterface
{
    private UserRepository $userRepository;

    /**
     * Constructs a new instance of UserManagerAdapter.
     *
     * @param UserRepository $userRepository The repository for managing user data.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Updates the last login time for a user.
     *
     * @param Uuid $userId The unique identifier of the user.
     * @param DateTimeImmutable $timestamp The login timestamp.
     * @return void
     */
    public function updateLastLogin(Uuid $userId, DateTimeImmutable $timestamp): void
    {
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $user->setLastLogin($timestamp);
            $this->userRepository->save($user);
        }
    }
}
