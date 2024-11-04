<?php

namespace App\SharedKernel\Audit;

use App\Domain\Common\Entity\EntityBase;
use App\Domain\UserManagement\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Service responsible for recording audit logs of significant events in the system.
 *
 * This service registers actions made on entities, such as property changes or lifecycle events.
 */
class AuditService
{
    /**
     * @var EntityManagerInterface The entity manager to handle persistence.
     */
    private EntityManagerInterface $entityManager;

    /**
     * AuditService constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Logs an action taken on an entity, recording details in the audit trail.
     *
     * @param string $action The action performed (e.g., "created", "updated", "deleted").
     * @param string $entity The entity affected by the action.
     * @param Uuid $entityId The Id of the entity affected by the action.
     * @param Uuid $performedBy The user who performed the action.
     * @param string $propertyName The name of the property that was changed (if applicable).
     * @param mixed|null $oldValue The previous value of the property.
     * @param mixed|null $newValue The new value of the property.
     *
     * @return void
     */
    public function logChange(
        string $action,
        string $entity,
        Uuid $entityId,
        Uuid $performedBy,
        string $propertyName,
        $oldValue = null,
        $newValue = null
    ): void {
        $auditEntry = new AuditTrail(
            action: $action,
            entity: $entity,
            entityId: $entityId,
            performedBy: $performedBy,
            propertyName: $propertyName,
            oldValue: $oldValue,
            newValue: $newValue
        );

        $this->entityManager->persist($auditEntry);
        $this->entityManager->flush();
    }

    /**
     * Logs a lifecycle event, such as session start or end, without specific property changes.
     *
     * @param string $action The lifecycle event (e.g., "SessionStarted", "SessionEnded").
     * @param string $entity The entity affected by the action.
     * @param Uuid $entityId The Id of the entity affected by the action.
     * @param Uuid $performedBy The user who performed the action.
     *
     * @return void
     */
    public function logLifecycleEvent(
        string $action, 
        string $entity,
        Uuid $entityId,
        Uuid $performedBy,
    ): void
    {
        $this->logChange(
            action: $action,
            entity: $entity,
            entityId: $entityId,
            performedBy: $performedBy,
            propertyName: 'lifecycle',
            oldValue: null,
            newValue: $action
        );
    }
}