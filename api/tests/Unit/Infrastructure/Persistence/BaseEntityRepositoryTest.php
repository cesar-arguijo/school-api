<?php

namespace App\Tests\Unit\Infrastructure\Persistence;

use App\Domain\Common\Entity\EntityBase;
use App\Infrastructure\Persistence\BaseEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Unit tests for the BaseEntityRepository class.
 *
 * This class verifies the behavior of BaseEntityRepository methods
 * using isolated mocks to ensure independence between tests.
 */
class BaseEntityRepositoryTest extends TestCase
{
    /**
     * Tests that findById retrieves an entity by its unique identifier.
     *
     * @return void
     */
    public function testFindById(): void
    {
         /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
         $entityManagerMock = $this->createMock(EntityManagerInterface::class);

         /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
         $repositoryMock = $this->createMock(ObjectRepository::class);
 
         $entityManagerMock->method('getRepository')->willReturn($repositoryMock);
 
         /** @var EntityBase|\PHPUnit\Framework\MockObject\MockObject $entity */
         $entity = $this->createMock(EntityBase::class);
         $repositoryMock->method('find')->willReturn($entity);
 
         $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);
         $result = $baseEntityRepository->findById(1);
 
         $this->assertSame($entity, $result);
    }

    /**
     * Tests that findAll retrieves all entities that are not soft-deleted.
     *
     * @return void
     */
    public function testFindAllWithoutDeleted(): void
    {
         /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
         $entityManagerMock = $this->createMock(EntityManagerInterface::class);

         /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
         $repositoryMock = $this->createMock(ObjectRepository::class);
 
         $entityManagerMock->method('getRepository')->willReturn($repositoryMock);
 
         $entities = [$this->createMock(EntityBase::class)];
         $repositoryMock->method('findBy')->with(['isDeleted' => false])->willReturn($entities);
 
         $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);
         $result = $baseEntityRepository->findAll();
 
         $this->assertSame($entities, $result);
    }

    /**
     * Tests that save calls persist and flush on the entity manager.
     *
     * @return void
     */
    public function testSave(): void
    {
        // Mock for ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);

        // Mock for EntityManagerInterface
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        // Mock for EntityBase
        /** @var EntityBase|\PHPUnit\Framework\MockObject\MockObject $entity */
        $entity = $this->createMock(EntityBase::class);

        // Expectations for persist and flush methods
        $entityManagerMock->expects($this->once())->method('persist')->with($entity);
        $entityManagerMock->expects($this->once())->method('flush');

        // Create instance of BaseEntityRepository
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);

        // Call the save method to test
        $baseEntityRepository->save($entity);
    }

    /**
     * Tests that softDelete marks an entity as deleted and flushes the entity manager.
     *
     * @return void
     */
    public function testSoftDelete(): void
    {
        // Mock for ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);

        // Mock for EntityManagerInterface
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        // Mock for EntityBase with method delete
        /** @var EntityBase|\PHPUnit\Framework\MockObject\MockObject $entity */
        $entity = $this->getMockBuilder(EntityBase::class)
                       ->onlyMethods(['delete'])
                       ->getMock();

        // Expectations: Entity delete method should be called once
        $entity->expects($this->once())->method('delete');

        // EntityManager should call flush once
        $entityManagerMock->expects($this->once())->method('flush');

        // Create instance of BaseEntityRepository
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);

        // Call the softDelete method to test
        $baseEntityRepository->softDelete($entity);
    }

    /**
     * Tests that delete removes an entity permanently and flushes the entity manager.
     *
     * @return void
     */
    public function testDelete(): void
    {
        // Mock for ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);

        // Mock for EntityManagerInterface
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        // Mock for EntityBase
        /** @var EntityBase|\PHPUnit\Framework\MockObject\MockObject $entity */
        $entity = $this->createMock(EntityBase::class);

        // Expectations: EntityManager should call remove with the entity and flush once
        $entityManagerMock->expects($this->once())->method('remove')->with($entity);
        $entityManagerMock->expects($this->once())->method('flush');

        // Create instance of BaseEntityRepository
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);

        // Call the delete method to test
        $baseEntityRepository->delete($entity);
    }

    /**
     * Tests that findAll retrieves all entities, including soft-deleted ones when requested.
     *
     * @return void
     */
    public function testFindAllWithDeleted(): void
    {
        // Mock ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);

        // Mock EntityManagerInterface
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        // Mock EntityBase entities
        $entities = [$this->createMock(EntityBase::class)];
        $repositoryMock->method('findAll')->willReturn($entities);

        // Create an instance of BaseEntityRepository
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);

        // Call findAll with $includeDeleted set to true
        $result = $baseEntityRepository->findAll(true);

        // Assert that the result matches the expected entities
        $this->assertSame($entities, $result);
    }

    /**
     * Tests that restore reverts an entity's soft deletion and flushes the entity manager.
     *
     * @return void
     */
    public function testRestore(): void
    {
         // Mock the EntityBase entity
        /** @var EntityBase|\PHPUnit\Framework\MockObject\MockObject $entity */
        $entity = $this->getMockBuilder(EntityBase::class)
        ->onlyMethods(['restore'])
        ->getMock();

        // Expect the restore method to be called once on the entity
        $entity->expects($this->once())->method('restore');

        // Mock the ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);

        // Mock the EntityManager and configure it to return the ObjectRepository
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);

        // Expect the flush method to be called once on the entity manager
        $entityManagerMock->expects($this->once())->method('flush');

        // Instantiate the BaseEntityRepository with the mocked EntityManager
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);

        // Call the restore method
        $baseEntityRepository->restore($entity);
    }

    /**
     * Tests that exists returns true when an entity with the specified identifier exists.
     *
     * @return void
     */
    public function testExistsReturnsTrueWhenEntityExists(): void
    {
        // Mock the ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);
        
        // Mock the EntityManager and configure it to return the ObjectRepository
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);
        
        // Mock the entity and configure the repository to return it when find is called
        $entity = $this->createMock(EntityBase::class);
        $repositoryMock->method('find')->willReturn($entity);
        
        // Instantiate the BaseEntityRepository with the mocked EntityManager
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);
        
        // Assert that exists returns true when the entity exists
        $result = $baseEntityRepository->exists(1);
        $this->assertTrue($result);
    }

    /**
     * Tests that exists returns false when no entity with the specified identifier is found.
     *
     * @return void
     */
    public function testExistsReturnsFalseWhenEntityDoesNotExist(): void
    {
        // Mock the ObjectRepository
        /** @var ObjectRepository|\PHPUnit\Framework\MockObject\MockObject $repositoryMock */
        $repositoryMock = $this->createMock(ObjectRepository::class);
        
        // Mock the EntityManager and configure it to return the ObjectRepository
        /** @var EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->method('getRepository')->willReturn($repositoryMock);
        
        // Configure the repository to return null when find is called
        $repositoryMock->method('find')->willReturn(null);
        
        // Instantiate the BaseEntityRepository with the mocked EntityManager
        $baseEntityRepository = new BaseEntityRepository($entityManagerMock, EntityBase::class);
        
        // Assert that exists returns false when the entity does not exist
        $result = $baseEntityRepository->exists(1);
        $this->assertFalse($result);
    }
}