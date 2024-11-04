<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Event\SessionStarted;
use App\Domain\UserManagement\Service\SessionMonitorService;
use App\SharedKernel\Audit\AuditService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles actions to be taken when a SessionStarted event is dispatched.
 *
 * This handler listens for the SessionStarted event and performs necessary
 * actions, such as monitoring session activity and controlling session limits.
 * 
 * @Attributes
 * - `sessionMonitor`: An instance of SessionMonitorService, used to track session activity.
 * - `event`: The SessionStarted event instance, containing user ID, device type, and timestamp.
 * 
 * @Event Emission
 * This handler is triggered by the dispatching of a SessionStarted event, which is raised when a new session is created.
 * 
 * @Usage Workflow
 * - **Session Creation**: When a new session is created, the SessionStarted event is emitted.
 * - **Event Dispatching**: UserService collects and dispatches the event through the event dispatcher.
 * - **Handler Execution**: SessionStartedHandler listens for the event and uses SessionMonitorService to track session activity.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Service\SessionService
 * @see App\Domain\UserManagement\Service\SessionMonitorService
 */
#[AsEventListener(event: SessionStarted::class)]
class SessionStartedHandler
{
    /**
     * Service for monitoring and managing user sessions.
     *
     * @var SessionMonitorService
     */
    private SessionMonitorService $sessionMonitor;

    /**
     * @var AuditService Logs actions to the audit trail.
     */
    private AuditService $auditService;

    /**
     * Constructs a new instance of SessionStartedHandler.
     *
     * @param SessionMonitorService $sessionMonitorService Service for monitoring sessions.
     * @param AuditService $auditService Service for creating audit log entries.
     */
    public function __construct(
        SessionMonitorService $sessionMonitorService,
        AuditService $auditService
    ) {
        $this->sessionMonitor = $sessionMonitorService;
        $this->auditService = $auditService;
    }


    /**
     * Handles the SessionStarted event by tracking session activity and controlling active sessions.
     *
     * @param SessionStarted $event The SessionStarted event instance.
     * @return void
     */
    public function __invoke(SessionStarted $event): void
    {
        // Track and monitor the session with user ID, device type, and timestamp
        $this->sessionMonitor->trackSession(
            $event->getUser(),
            $event->getSession(),
            $event->getTimestamp()
        );
        
        // Log a lifecycle audit entry for session start
        $this->auditService->logLifecycleEvent(
            action: 'SessionStarted',
            entity: $event->getSession(),
            performedBy: $event->getUser(),
        );
    }
}
