<?php

namespace App\Infrastructure\Persistence;

use App\Domain\UserManagement\User\DataProvider\UserDataProviderInterface;
use App\Domain\UserManagement\User\Entity\User;
use App\Domain\UserManagement\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserRepository
 *
 * Implements UserRepositoryInterface, providing custom user operations.
 * Extends BaseEntityRepository for common CRUD and soft delete functionalities.
 */
class UserRepository extends BaseEntityRepository implements UserRepositoryInterface, UserDataProviderInterface
{
    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    /**
     * Finds a user by their email address.
     *
     * @param string $email The email address to search for.
     * @return User|null The user with the specified email, or null if not found.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }
}
