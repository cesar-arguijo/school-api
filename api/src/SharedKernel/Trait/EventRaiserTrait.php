<?php

namespace App\SharedKernel\Trait;

/**
 * Trait for raising domain events.
 *
 * Provide methods for raising and retrieving domain events.
 * 
 */
trait EventRaiserTrait
{
    /**
     * @var array List of domain events raised by the entity.
     */
    private array $events = [];

    /**
     * {@inheritDoc}
     */
    public function raiseEvent(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * {@inheritDoc}
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * {@inheritDoc}
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }
}
