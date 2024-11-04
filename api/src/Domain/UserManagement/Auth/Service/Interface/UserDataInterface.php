<?php

namespace App\Domain\UserManagement\Auth\Repository;

use App\Domain\UserManagement\Auth\ValueObject\Role;
use Symfony\Component\Uid\Uuid;

/**
 * Interface for managing user data, including role and permission updates.
 */
interface UserDataInterface
{
    public function findById(Uuid $userId);
    public function updateUserRolePermissions(Uuid $userId, Role $newRole): void;
}