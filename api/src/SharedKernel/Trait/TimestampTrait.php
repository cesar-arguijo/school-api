<?php

namespace App\SharedKernel\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait for timestamp functionality.
 *
 * Implements the TimestampableInterface, allowing an entity to track
 * the creation and update timestamps.
 */
trait TimestampTrait
{
    /**
     * @var \DateTime $createdAt The creation timestamp of the entity.
     */
    #[ORM\Column(type: "datetime")]
    #[Assert\NotNull]
    #[Assert\DateTime]
    #[Assert\LessThanOrEqual("now", message: "The creation date cannot be in the future.")]
    private \DateTime $createdAt;

    /**
     * @var \DateTime $updatedAt The last update timestamp of the entity.
     */
    #[ORM\Column(type: "datetime")]
    #[Assert\NotNull]
    #[Assert\DateTime]
    #[Assert\LessThanOrEqual("now", message: "The update date cannot be in the future.")]
    private \DateTime $updatedAt;

    /**
     * Sets the creation and update timestamps before persisting.
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Sets the update timestamp before updating.
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
