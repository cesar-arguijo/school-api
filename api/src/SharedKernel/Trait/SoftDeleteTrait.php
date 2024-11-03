<?php

namespace App\SharedKernel\Trait;

use App\SharedKernel\Contract\SoftDeletableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait for soft deletion functionality.
 *
 * Implements the SoftDeletableInterface, allowing an entity to be marked
 * as deleted without physical removal from the database.
 */
trait SoftDeleteTrait
{
    /**
     * @var bool $isDeleted Whether the entity is marked as deleted.
     */
    #[ORM\Column(type: "boolean")]
    private bool $isDeleted = false;

    /**
     * {@inheritDoc}
     */
    public function delete(): void
    {
        $this->isDeleted = true;
    }

    /**
     * {@inheritDoc}
     */
    public function restore(): void
    {
        $this->isDeleted = false;
    }

    /**
     * {@inheritDoc}
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }
}