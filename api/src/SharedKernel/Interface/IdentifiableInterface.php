<?php

namespace App\SharedKernel\Interface;

use Symfony\Component\Uid\Uuid;

/**
 * Interface for entities with a unique identifier.
 * 
 * Provides a contract for entities that use UUIDs as unique identifiers.
 */
interface IdentifiableInterface
{
    /**
     * Retrieves the unique identifier of the entity.
     *
     * @return Uuid The UUID of the entity.
     */
    public function getId(): Uuid;
}