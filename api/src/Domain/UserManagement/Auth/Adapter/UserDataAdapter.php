<?php

namespace App\Infrastructure\Auth\Repository;

use App\Domain\UserManagement\Auth\Repository\UserDataInterface;
use App\Domain\UserManagement\Auth\ValueObject\Role;
use App\Domain\UserManagement\User\Entity\User;
use App\Infrastructure\Persistence\UserRepository;
use Symfony\Component\Uid\Uuid;

/**
 * Adapter for UserRepository to handle user data in Auth.
 */
class UserDataAdapter implements UserDataInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findById(Uuid $userId)
    {
        return $this->userRepository->findById($userId);
    }

    public function updateUserRolePermissions(Uuid $userId, Role $newRole): void
    {
        /** @var User */
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $user->updatePermissionsBasedOnRole($newRole);
            $this->userRepository->save($user);
        }
    }
}