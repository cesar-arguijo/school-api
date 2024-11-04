<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Event\SessionEnded;
use App\Domain\UserManagement\Service\SessionManagementService;
use App\SharedKernel\Audit\AuditService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles the SessionEnded event.
 *
 * Logs the session end in the audit trail and performs other cleanup operations.
 *
 * @Attributes
 * @AsEventListener(event: SessionEnded::class)
 */
class SessionEndedHandler
{
    /**
     * @var AuditService The audit service for logging audit trails.
     */
    private AuditService $auditService;

    /**
     * @var UserRepository Repository to access user-related data.
     */

    private SessionManagementService $sessionManagementService;

    /**
     * SessionEndedHandler constructor.
     *
     * @param AuditService $auditService The audit service to record audit logs.
     * @param SessionManagementService $sessionManagementService The repository to manage session data.
     */
    public function __construct(
        AuditService $auditService,
        SessionManagementService $sessionManagementService
    ) {
        $this->auditService = $auditService;
        $this->sessionManagementService = $sessionManagementService;
    }

    /**
     * Handles the SessionEnded event by logging it to the audit trail.
     *
     * @param SessionEnded $event The event containing session details.
     * @return void
     */
    public function __invoke(SessionEnded $event): void
    {
       
        $user = $event->getUser();
        $session = $event->getSession();

        if (!$user || !$session) {
            return; // Exit if the user is not found
        }

       // End session and update active session count
       $this->sessionManagementService->endSessionAndUpdateCount($session, $user);

       // Log session end in the audit trail
       $this->auditService->logLifecycleEvent('SessionEnded', $session, $user);
    }
}
