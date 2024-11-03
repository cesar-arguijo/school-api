<?php

namespace App\Domain\Common\Repository;

use App\Domain\Common\Entity\EntityBase;

/**
 * Interface BaseEntityRepositoryInterface
 *
 * Base repository interface for generic repository operations.
 * This is designed to support both DDD and integration with API Platform
 * by exposing methods that handle common CRUD and soft delete operations.
 */
interface BaseEntityRepositoryInterface
{
    /**
     * Finds an entity by its unique identifier.
     *
     * @param mixed $id The unique identifier of the entity.
     * @return EntityBase|null The entity, or null if not found.
     */
    public function findById($id): ?EntityBase;

    /**
     * Retrieves all entities, with optional filtering for non-deleted records.
     *
     * @param bool $includeDeleted Whether to include soft-deleted entities.
     * @return EntityBase[] List of all entities.
     */
    public function findAll(bool $includeDeleted = false): array;

    /**
     * Saves an entity to the repository, handling creation or update as needed.
     *
     * @param EntityBase $entity The entity to save.
     * @return void
     */
    public function save(EntityBase $entity): void;

    /**
     * Marks an entity as deleted (soft delete).
     *
     * @param EntityBase $entity The entity to soft delete.
     * @return void
     */
    public function softDelete(EntityBase $entity): void;

    /**
     * Restores a soft-deleted entity.
     *
     * @param EntityBase $entity The entity to restore.
     * @return void
     */
    public function restore(EntityBase $entity): void;

    /**
     * Deletes an entity from the repository (permanent deletion).
     *
     * This method is separate from soft delete for cases where physical deletion is necessary.
     *
     * @param EntityBase $entity The entity to delete permanently.
     * @return void
     */
    public function delete(EntityBase $entity): void;

    /**
     * Checks if an entity exists by its identifier.
     *
     * @param mixed $id The unique identifier of the entity.
     * @return bool True if the entity exists, false otherwise.
     */
    public function exists($id): bool;
}