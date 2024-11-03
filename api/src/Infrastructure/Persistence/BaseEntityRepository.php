<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Common\Entity\EntityBase;
use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class BaseEntityRepository
 *
 * Implements BaseEntityRepositoryInterface, providing common persistence
 * operations for entities that extend EntityBase. Designed for use with
 * Domain-Driven Design and API Platform, supporting soft delete and restoration.
 */
class BaseEntityRepository implements BaseEntityRepositoryInterface
{
    /** 
     * @var EntityManagerInterface The entity manager instance.
     */
    protected EntityManagerInterface $entityManager;

    /** 
     * @var ObjectRepository The specific entity repository.
     */
    protected ObjectRepository $repository;

    /**
     * BaseEntityRepository constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager to manage database operations.
     * @param string $entityClass The class name of the entity managed by this repository.
     */
    public function __construct(EntityManagerInterface $entityManager, string $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($entityClass);
    }

    /**
     * Finds an entity by its unique identifier.
     *
     * @param mixed $id The unique identifier of the entity.
     * @return EntityBase|null The entity, or null if not found.
     */
    public function findById($id): ?EntityBase
    {
        return $this->repository->find($id);
    }

    /**
     * Retrieves all entities, with optional filtering for non-deleted records.
     *
     * @param bool $includeDeleted Whether to include soft-deleted entities.
     * @return EntityBase[] List of all entities.
     */
    public function findAll(bool $includeDeleted = false): array
    {
        if ($includeDeleted) {
            return $this->repository->findAll();
        }

        return $this->repository->findBy(['isDeleted' => false]);
    }

    /**
     * Saves an entity to the repository, handling creation or update as needed.
     *
     * @param EntityBase $entity The entity to save.
     * @return void
     */
    public function save(EntityBase $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * Marks an entity as deleted (soft delete).
     *
     * @param EntityBase $entity The entity to soft delete.
     * @return void
     */
    public function softDelete(EntityBase $entity): void
    {
        $entity->delete();
        $this->entityManager->flush();
    }

    /**
     * Restores a soft-deleted entity.
     *
     * @param EntityBase $entity The entity to restore.
     * @return void
     */
    public function restore(EntityBase $entity): void
    {
        $entity->restore();
        $this->entityManager->flush();
    }

    /**
     * Deletes an entity from the repository (permanent deletion).
     *
     * This method is separate from soft delete for cases where physical deletion is necessary.
     *
     * @param EntityBase $entity The entity to delete permanently.
     * @return void
     */
    public function delete(EntityBase $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * Checks if an entity exists by its identifier.
     *
     * @param mixed $id The unique identifier of the entity.
     * @return bool True if the entity exists, false otherwise.
     */
    public function exists($id): bool
    {
        return (bool)$this->repository->find($id);
    }
}