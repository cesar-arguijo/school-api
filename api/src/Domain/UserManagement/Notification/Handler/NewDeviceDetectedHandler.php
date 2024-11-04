<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Event\NewDeviceDetected;
use App\Domain\UserManagement\Service\NotificationService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles the NewDeviceDetected event by notifying the user of a new device login.
 * 
 * @Attributes
 * - `notificationService`: Service for sending security alerts.
 * 
 * @Event Emission
 * - Listens for the `NewDeviceDetected` event.
 * - Responds by notifying the user about the new device detected during login.
 * 
 * @Usage Workflow
 * 1. The `NewDeviceDetected` event is raised by `SessionMonitorService`.
 * 2. `NewDeviceDetectedHandler` listens to the event and triggers a security alert for the user.
 * 
 * @See Also
 * @see App\Domain\UserManagement\Event\NewDeviceDetected
 * @see App\Domain\UserManagement\Service\NotificationService
 */
#[AsEventListener(event: NewDeviceDetected::class)]
class NewDeviceDetectedHandler
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
     * Invokes the handler to alert the user of a new device login.
     *
     * @param NewDeviceDetected $event The event instance containing user ID, device type, and timestamp.
     * @return void
     */
    public function __invoke(NewDeviceDetected $event): void
    {
        $userId = $event->getUser();
        $deviceType = $event->getDevice()->getDeviceType();

        // Send a security alert notification via NotificationService
        $this->notificationService->notifyNewDeviceDetected($userId, $deviceType, $event->getTimestamp());
    }
}
