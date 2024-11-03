<?php

namespace App\SharedKernel\Interface;
/**
 * Interface for entities that are versioned.
 * 
 * Implementing this interface allows an entity to track and retrieve 
 * a version number, which can be useful for optimistic locking 
 * or tracking modifications.
 */
interface VersionableInterface
{
    /**
     * Retrieves the version number of the entity.
     *
     * @return int The current version number.
     */
    public function getVersion(): int;
}
