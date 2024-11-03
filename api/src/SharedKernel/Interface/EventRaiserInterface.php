<?php

namespace App\SharedKernel\Interface;

/**
 * Interface for raising and managing domain events.
 *
 * Provides a contract for any class that needs to manage domain events.
 */
interface EventRaiserInterface
{
    /**
     * Raises a new domain event.
     *
     * @param object $event The domain event to raise.
     */
    public function raiseEvent(object $event): void;

    /**
     * Retrieves all raised domain events.
     *
     * @return array List of domain events raised by the entity.
     */
    public function getEvents(): array;

    /**
     * Clears all raised domain events.
     */
    public function clearEvents(): void;
}