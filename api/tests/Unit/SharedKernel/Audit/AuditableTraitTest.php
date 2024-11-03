<?php 

namespace App\Tests\Unit\SharedKernel\Audit;

use App\SharedKernel\Audit\AuditTrail;
use App\Tests\Unit\SharedKernel\Audit\TestEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the AuditableTrait.
 */
class AuditableTraitTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManager;

    protected function setUp(): void
    {
        // Create a mock for the EntityManagerInterface
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

     /**
     * Tests the getId method of the AuditTrail entity.
     *
     * @return void
     */
    public function testGetId(): void
    {
        // Create a new AuditTrail instance
        $auditTrail = new AuditTrail('12345', 'property_name', 'old_value', 'new_value');

        // Use reflection to set the id property manually
        $reflection = new \ReflectionClass(AuditTrail::class);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($auditTrail, 1); // Manually set the ID

        // Verify that getId returns the expected ID
        $this->assertEquals(1, $auditTrail->getId());
    }
    /**
     * Tests the auditChanges method in AuditableTrait.
     *
     * @return void
     */
    public function testAuditChanges(): void
    {
        // Create an instance of TestEntity that uses AuditableTrait
        $testEntity = new TestEntity();
        $testEntity->setName('Initial Name');

        // Use a stub for PreUpdateEventArgs
        /** @var PreUpdateEventArgs&MockObject $args */
        $args = $this->getMockBuilder(PreUpdateEventArgs::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Simulate the change set to return a property change
        $args->method('getEntityChangeSet')
             ->willReturn(['name' => ['Initial Name', 'Updated Name']]);

        // Mock the getObjectManager method to return our entityManager mock
        $args->method('getObjectManager')
             ->willReturn($this->entityManager);

        // Expect that the persist method is called once with an AuditTrail instance
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (AuditTrail $auditTrail) {
                return $auditTrail->getPropertyName() === 'name' &&
                       $auditTrail->getOldValue() === json_encode('Initial Name') &&
                       $auditTrail->getNewValue() === json_encode('Updated Name');
            }));

        // Execute the auditChanges method
        $testEntity->auditChanges($args);
    }
}
