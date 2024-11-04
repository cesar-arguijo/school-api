<?php

namespace App\Domain\UserManagement\User\Adapter;

use App\SharedKernel\Audit\AuditService;
use Symfony\Component\Uid\Uuid;

/**
 * AuditAdapter
 *
 * This adapter serves as an intermediary to the AuditService, encapsulating
 * audit logging for actions taken on entities. It offers both lifecycle event
 * logging and detailed change logging.
 *
 * **Attributes**
 *
 * - `AuditService`: The main service used to record audit logs.
 *
 * **Usage**
 *
 * This adapter is primarily used by the User subdomain to log key actions and
 * track modifications made to entities like `User` and related components. 
 * The adapter chooses the appropriate logging method based on the type of event.
 */
class AuditAdapter
{
    private AuditService $auditService;

    /**
     * Constructs a new instance of the AuditAdapter.
     *
     * @param AuditService $auditService The service used to record audit logs.
     */
    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Logs an audit entry for a significant action on an entity, allowing
     * for lifecycle events or detailed property changes.
     *
     * **Lifecycle Event Logging**
     * If the property name is 'lifecycle', this logs a high-level event.
     *
     * **Detailed Change Logging**
     * For other property names, this logs both the old and new values to 
     * capture the specific change in state.
     *
     * @param string $action The action performed (e.g., "created", "updated").
     * @param string $entity The entity affected by the action.
     * @param Uuid $entityId The ID of the affected entity.
     * @param Uuid $performedBy The ID of the user performing the action.
     * @param string $propertyName The name of the changed property, defaults to 'lifecycle'.
     * @param mixed|null $oldValue The previous value of the property, if applicable.
     * @param mixed|null $newValue The new value of the property, if applicable.
     * @return void
     */
    public function logAudit(
        string $action,
        string $entity,
        Uuid $entityId,
        Uuid $performedBy,
        string $propertyName = 'lifecycle',
        $oldValue = null,
        $newValue = null
    ): void {
        if ($propertyName === 'lifecycle') {
            // Logs a general lifecycle event
            $this->auditService->logLifecycleEvent(
                $action, 
                $entity,
                $entityId, 
                $performedBy);
        } else {
            // Logs a specific property change with previous and new values
            $this->auditService->logChange(
                $action, 
                $entity, 
                $entityId,
                $performedBy, 
                $propertyName, 
                $oldValue, 
                $newValue);
        }
    }
}
