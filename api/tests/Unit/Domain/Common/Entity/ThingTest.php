<?php

namespace App\Tests\Domain\Common\Entity;

use App\Domain\Common\Entity\Thing;
use PHPUnit\Framework\TestCase;

/**
 * Concrete class extending Thing for testing purposes.
 *
 * This concrete class is used to allow instantiation of the abstract Thing class
 * within unit tests.
 */
class ConcreteThing extends Thing
{
    // No additional implementation needed for testing
}

/**
 * Unit tests for the Thing class.
 *
 * This test suite verifies the functionality of the Thing class, including the ability to 
 * set and retrieve the name property.
 */
class ThingTest extends TestCase
{
    /**
     * @var Thing An instance of Thing used for testing.
     */
    private Thing $thing;

     /**
     * Sets up the test environment by creating a new instance of ConcreteThing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->thing = new ConcreteThing('Test Name');
    }

    /**
     * Tests that the Thing instance is created with the specified name.
     *
     * This test checks that the name provided during instantiation is correctly stored and 
     * can be retrieved using the getName() method.
     *
     * @return void
     */
    public function testThingCreationWithName(): void
    {
        $this->assertEquals('Test Name', $this->thing->getName());
    }

     /**
     * Tests setting and getting the name property.
     *
     * This test verifies that the setName() method correctly updates the name of the 
     * Thing instance, and that the new name can be retrieved using getName().
     *
     * @return void
     */
    public function testSetName(): void
    {
        $newName = 'Updated Name';
        $this->thing->setName($newName);

        $this->assertEquals($newName, $this->thing->getName());
    }
}
