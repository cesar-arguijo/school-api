<?php

namespace App\Tests\Unit\SharedKernel\Traits;

use PHPUnit\Framework\TestCase;
use App\SharedKernel\Trait\EventRaiserTrait;

/**
 * Class EventRaiserTraitTest
 *
 * Tests the EventRaiserTrait functionality for raising, retrieving, and clearing events.
 */
class EventRaiserTraitTest extends TestCase
{
    private $eventRaiser;

    protected function setUp(): void
    {
        // Crear una clase anónima que use el trait para las pruebas
        $this->eventRaiser = new class {
            use EventRaiserTrait;
        };
    }

    /**
     * Test that an event can be raised and retrieved.
     */
    public function testRaiseEvent(): void
    {
        $event = new \stdClass();  // Crea un objeto de evento de prueba
        $this->eventRaiser->raiseEvent($event);
        
        $events = $this->eventRaiser->getEvents();
        $this->assertCount(1, $events, "Failed asserting that one event was raised.");
        $this->assertSame($event, $events[0], "Failed asserting that the raised event is the same as retrieved.");
    }

    /**
     * Test that multiple events can be raised and retrieved.
     */
    public function testRaiseMultipleEvents(): void
    {
        $event1 = new \stdClass();
        $event2 = new \stdClass();
        
        $this->eventRaiser->raiseEvent($event1);
        $this->eventRaiser->raiseEvent($event2);
        
        $events = $this->eventRaiser->getEvents();
        $this->assertCount(2, $events, "Failed asserting that two events were raised.");
        $this->assertSame($event1, $events[0], "Failed asserting that the first event is as expected.");
        $this->assertSame($event2, $events[1], "Failed asserting that the second event is as expected.");
    }

    /**
     * Test that events can be cleared.
     */
    public function testClearEvents(): void
    {
        $event = new \stdClass();
        $this->eventRaiser->raiseEvent($event);
        
        $this->eventRaiser->clearEvents();
        
        $events = $this->eventRaiser->getEvents();
        $this->assertCount(0, $events, "Failed asserting that events were cleared.");
    }
}
