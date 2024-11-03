<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Event\SessionLimitExceeded;
use App\Domain\UserManagement\Service\NotificationService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles the SessionLimitExceeded event by notifying administrators or users.
 * 
 * @Attributes
 * - `notificationService`: Service for sending notifications to administrators or users.
 * 
 * @Event Emission
 * - Listens for the `SessionLimitExceeded` event.
 * - Responds by notifying the user or administrator of the session limit exceedance.
 * 
 * @Usage Workflow
 * 1. The `SessionLimitExceeded` event is raised by `SessionMonitorService`.
 * 2. `SessionLimitExceededHandler` listens to the event and triggers a notification to inform the necessary parties.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Event\SessionLimitExceeded
 * @see App\Domain\UserManagement\Service\NotificationService
 */
#[AsEventListener(event: SessionLimitExceeded::class)]
class SessionLimitExceededHandler
{
    /**
     * @var NotificationService
     */
    private NotificationService $notificationService;

    /**
     * Constructor.
     *
     * @param NotificationService $notificationService The service for sending notifications.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Invokes the handler to notify users/admins of a session limit exceedance.
     *
     * @param SessionLimitExceeded $event The event instance containing user ID, session limit, and timestamp.
     * @return void
     */
    public function __invoke(SessionLimitExceeded $event): void
    {
        /** @var User */
        $user = $event->getUser();
        $userId = $user->getId();
        $limit = $event->getLimit();

        // Notify via NotificationService
        $this->notificationService->notifySessionLimitExceeded($userId, $limit, $event->getTimestamp());
    }
}
