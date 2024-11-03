<?php

namespace App\SharedKernel\Audit;

use App\SharedKernel\Audit\AuditTrail;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Trait for adding audit capabilities to an entity.
 *
 * Tracks changes to entity properties and logs them in an AuditTrail.
 */
trait AuditableTrait
{
    /**
     * Creates an audit log entry whenever the entity is updated.
     *
     * @ORM\PreUpdate
     *
     * @param PreUpdateEventArgs $args The event arguments containing the entity and changes.
     * @return void
     */
    public function auditChanges(PreUpdateEventArgs $args): void
    {
        $changeset = $args->getEntityChangeSet();

        // Access the entity manager without directly calling getEntityManager on PreUpdateEventArgs
        foreach ($changeset as $property => [$oldValue, $newValue]) {
            $auditEntry = new AuditTrail($this->getId()->toRfc4122(), $property, $oldValue, $newValue);
            
            // Obtain the Entity Manager from the Doctrine Event Manager
            $args->getObjectManager()->persist($auditEntry);
        }

        // No need to flush here as Doctrine will handle it post-event
    }
}