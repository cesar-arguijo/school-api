<?php

namespace App\SharedKernel\Interface;

/**
 * Interface for entities that support soft deletion.
 * 
 * Implementing this interface allows an entity to be marked as "deleted"
 * without being removed from the database, and to be restored if needed.
 */
interface SoftDeletableInterface
{
    /**
     * Marks the entity as deleted.
     *
     * @return void
     */
    public function delete(): void;

    /**
     * Checks if the entity is marked as deleted.
     *
     * @return bool True if the entity is deleted, false otherwise.
     */
    public function isDeleted(): bool;

    /**
     * Restores the entity by unmarking it as deleted.
     *
     * @return void
     */
    public function restore(): void;
}
