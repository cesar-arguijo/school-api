<?php

namespace App\SharedKernel\Interface;

/**
 * Interface for timestamped entities.
 * 
 * Provides methods to track the creation and update times of an entity.
 */
interface TimestampableInterface
{
    /**
     * Retrieves the creation date and time of the entity.
     *
     * @return \DateTime The creation timestamp.
     */
    public function getCreatedAt(): \DateTime;

    /**
     * Retrieves the last update date and time of the entity.
     *
     * @return \DateTime The last update timestamp.
     */
    public function getUpdatedAt(): \DateTime;
}