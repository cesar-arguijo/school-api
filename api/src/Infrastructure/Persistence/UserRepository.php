<?php

namespace App\Infrastructure\Persistence;

use App\Domain\UserManagement\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * UserRepository
 *
 * Manages `User` entities by providing specific data retrieval methods
 * and centralizing user-related data access logic.
 *
 * **Purpose**
 * 
 * This repository encapsulates data access logic for `User` entities. It extends BaseEntityRepository
 * to leverage common repository methods and enriches them with user-specific data access functions.
 *
 * **Methods Overview**
 * 
 * - *findByEmail*: Finds a user by their email address.
 * - *findByRole*: Retrieves a list of users with a specified role.
 * - *findRecentActiveUsers*: Retrieves a list of active users who registered in the last 30 days.
 *
 * **Usage Workflow**
 * 
 * - *Dependency Injection*: Inject the `UserRepository` where user data access is required, like in application services or domain logic.
 * - *Fetching Users by Email*: Use `findByEmail` to locate users for operations like authentication or profile management.
 * - *Retrieving Users by Role*: Leverage `findByRole` to segment users by role, which is useful in role-based access control or reporting.
 * - *Identifying Recent Active Users*: Use `findRecentActiveUsers` for insights into new active users, supporting user engagement analytics.
 *
 * @see User The `User` entity managed by this repository.
 * @see BaseEntityRepository The base repository that provides common data access methods.
 */
class UserRepository extends BaseEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager to manage database operations.
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

    /**
     * Finds users by role.
     *
     * @param string $role The role to filter users by.
     * @return User[] A list of users with the specified role.
     */
    public function findByRole(string $role): array
    {
        return $this->repository->findBy(['role' => $role]);
    }

    /**
     * Finds active users registered in the last 30 days.
     *
     * @return User[] A list of recently registered active users.
     */
    public function findRecentActiveUsers(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        return $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.isActive = :active')
            ->andWhere('u.createdAt > :date')
            ->setParameter('active', true)
            ->setParameter('date', new \DateTime('-30 days'))
            ->getQuery()
            ->getResult();
    }


}