<?php

namespace App\Infrastructure\Persistence;

use App\Domain\UserManagement\Entity\Session;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Repository for managing Session entities.
 *
 * This repository provides additional methods specific to handling user sessions
 * while inheriting common persistence functionalities from BaseEntityRepository.
 */
class SessionRepository extends BaseEntityRepository
{
    /**
     * SessionRepository constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager to manage database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Session::class);
    }

    /**
     * Finds active sessions by user ID, filtering out expired sessions.
     *
     * @param int $userId The ID of the user whose active sessions to find.
     * @return Session[] An array of active sessions for the specified user.
     */
    public function findActiveSessionsByUserId(int $userId): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        return $qb->select('s')
            ->from(Session::class, 's')
            ->where('s.user = :userId')
            ->andWhere('s.expiresAt > :currentDate')
            ->andWhere('s.endedAt IS NULL')
            ->setParameter('userId', $userId)
            ->setParameter('currentDate', new DateTime())
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Ends all sessions for a specific user, typically used when enforcing single-session limits.
     *
     * @param int $userId The ID of the user whose sessions should be ended.
     * @return void
     */
    public function endAllSessionsForUser(int $userId): void
    {
        $sessions = $this->findActiveSessionsByUserId($userId);
        foreach ($sessions as $session) {
            $session->end();
            $this->save($session);
        }
    }

     /**
     * Counts the active (non-expired) sessions for a given user.
     *
     * @param string $userId The ID of the user.
     * @return int The count of active sessions for the user.
     */
    public function countActiveSessions(string $userId): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return (int) $queryBuilder->select('COUNT(s.id)')
            ->from(Session::class, 's')
            ->where('s.user = :userId')
            ->andWhere('s.expiresAt > :currentDate')
            ->andWhere('s.endedAt IS NULL')
            ->setParameter('userId', $userId)
            ->setParameter('currentDate', new DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
