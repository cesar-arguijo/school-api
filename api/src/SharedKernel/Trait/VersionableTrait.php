<?php

namespace App\SharedKernel\Trait;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait for version control functionality.
 *
 * Implements the VersionableInterface, allowing an entity to maintain a version number
 * for concurrency control.
 */
trait VersionableTrait
{
    /**
     * @var int $version The version number of the entity.
     */
    #[ORM\Column(type: "integer")]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[ORM\Version]
    private int $version = 1;

    /**
     * {@inheritDoc}
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
