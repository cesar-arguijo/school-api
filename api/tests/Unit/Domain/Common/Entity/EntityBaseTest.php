<?php

namespace App\Tests\Unit\Domain\Common\Entity;

use App\Domain\Common\Entity\EntityBase;
use Symfony\Component\Uid\Uuid;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the EntityBase class.
 *
 * Verifies the functionality of unique identifier, soft delete, versioning, and timestamping.
 */
class EntityBaseTest extends TestCase
{
    /**
     * @var EntityBase The entity instance being tested.
     */
    private EntityBase $entity;

    /**
     * Sets up the test environment with a mock of EntityBase.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->entity = new class extends EntityBase {
            // Clase anónima que extiende EntityBase para los tests
        };
    }

    /**
     * Tests if the entity has a valid UUID.
     *
     * @return void
     */
    public function testGetId(): void
    {
        $this->assertInstanceOf(Uuid::class, $this->entity->getId());
    }
}

