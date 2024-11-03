<?php

namespace App\Tests\Infrastructure\StateProcessor;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Common\Entity\EntityBase;
use ApiPlatform\Metadata\Delete;
use App\Infrastructure\StateProcessor\BaseStateProcessor;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for BaseStateProcessor, which handles persistence
 * and soft deletion logic for API Platform entities.
 */
class BaseStateProcessorTest extends TestCase
{
    /**
     * Tests that process persists an entity for non-delete operations.
     *
     * @return void
     */
    public function testProcessPersistsEntityForNonDeleteOperation(): void
    {
        // Create a mock EntityManagerInterface
        /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        // Mock EntityBase entity
        /** @var EntityBase&\PHPUnit\Framework\MockObject\MockObject $entity */
        $entity = $this->createMock(EntityBase::class);

        // Mock a non-delete operation
        /** @var Operation&\PHPUnit\Framework\MockObject\MockObject $operation */
        $operation = $this->createMock(Operation::class);

        // Expect persist and flush methods to be called on the entity manager
        $entityManagerMock->expects($this->once())->method('persist')->with($entity);
        $entityManagerMock->expects($this->once())->method('flush');

        // Instantiate BaseStateProcessor and call process method
        $processor = new BaseStateProcessor($entityManagerMock);
        $result = $processor->process($entity, $operation);

        // Assert that the returned result is the same entity
        $this->assertSame($entity, $result);
    }

    /**
     * Tests that process applies soft deletion for delete operations.
     *
     * @return void
     */
    public function testProcessSoftDeletesEntityForDeleteOperation(): void
    {
        // Create a mock EntityManagerInterface
        /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        // Mock EntityBase entity with a soft delete method expectation
        /** @var EntityBase&\PHPUnit\Framework\MockObject\MockObject $entity */
        $entity = $this->getMockBuilder(EntityBase::class)
            ->onlyMethods(['delete'])
            ->getMock();

        // Expect the delete method to be called once on the entity
        $entity->expects($this->once())->method('delete');

        $operation = new Delete();
        $this->assertInstanceOf(DeleteOperationInterface::class, $operation); // Ensuring it behaves as DeleteOperationInterface

        // Expect flush to be called on the entity manager
        $entityManagerMock->expects($this->once())->method('flush');

        // Instantiate BaseStateProcessor and call process method
        $processor = new BaseStateProcessor($entityManagerMock);
        $result = $processor->process($entity, $operation);

        // Assert that the returned result is the same entity
        $this->assertSame($entity, $result);
    }

    /**
     * Tests that process returns null when the data is not an EntityBase instance.
     *
     * @return void
     */
    public function testProcessReturnsNullForInvalidData(): void
    {
        // Create a mock EntityManagerInterface
        /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        // Use a non-EntityBase instance as data
        $data = new \stdClass();

        // Mock a non-delete operation
        /** @var Operation&\PHPUnit\Framework\MockObject\MockObject $operation */
        $operation = $this->createMock(Operation::class);

        // Instantiate BaseStateProcessor and call process method
        $processor = new BaseStateProcessor($entityManagerMock);
        $result = $processor->process($data, $operation);

        // Assert that the result is null
        $this->assertNull($result);
    }
}
