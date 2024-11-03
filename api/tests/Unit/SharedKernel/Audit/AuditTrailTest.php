<?php

namespace App\Tests\Unit\SharedKernel\Audit\Entity;

use App\SharedKernel\Audit\AuditTrail;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the AuditTrail entity.
 */
class AuditTrailTest extends TestCase
{
    /**
     * Tests the initialization of the AuditTrail entity and its getters.
     *
     * @return void
     */
    public function testAuditTrailInitialization(): void
    {
        $entityId = '12345';
        $propertyName = 'name';
        $oldValue = 'old_name';
        $newValue = 'new_name';
        
        $auditTrail = new AuditTrail($entityId, $propertyName, $oldValue, $newValue);

        // Assert that the properties are set correctly
        $this->assertEquals($entityId, $auditTrail->getEntityId());
        $this->assertEquals($propertyName, $auditTrail->getPropertyName());
        $this->assertEquals(json_encode($oldValue), $auditTrail->getOldValue());
        $this->assertEquals(json_encode($newValue), $auditTrail->getNewValue());
        $this->assertInstanceOf(\DateTime::class, $auditTrail->getChangedAt());
    }
    
    /**
     * Tests the setters for AuditTrail entity.
     *
     * @return void
     */
    public function testAuditTrailSetters(): void
    {
        $auditTrail = new AuditTrail('12345', 'name', 'old_name', 'new_name');

        $auditTrail->setEntityId('67890');
        $auditTrail->setPropertyName('status');
        $auditTrail->setOldValue('inactive');
        $auditTrail->setNewValue('active');
        $auditTrail->setChangedAt(new \DateTime('2024-01-01 12:00:00'));

        $this->assertEquals('67890', $auditTrail->getEntityId());
        $this->assertEquals('status', $auditTrail->getPropertyName());
        $this->assertEquals(json_encode('inactive'), $auditTrail->getOldValue());
        $this->assertEquals(json_encode('active'), $auditTrail->getNewValue());
        $this->assertEquals('2024-01-01 12:00:00', $auditTrail->getChangedAt()->format('Y-m-d H:i:s'));
    }
}
