<?php

namespace App\Domain\UserManagement\EventHandler;

use App\Domain\UserManagement\Event\PasswordChanged;
use App\Domain\UserManagement\Service\NotificationService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles actions to be taken when a PasswordChanged event is dispatched.
 *
 * ## Attributes
 * - **notificationService**: Used to notify users about password changes.
 *
 * ## Event Emission
 * This handler listens for the PasswordChanged event and performs necessary actions
 * such as sending notifications.
 *
 * ## Usage Workflow
 * 1. **Event Dispatching**: When a user changes their password, the PasswordChanged
 * event is dispatched by the UserService.
 * 2. **Handling**: PasswordChangedHandler listens to the event and sends a notification
 * to the user.
 *
 * ## See Also
 * - {@see UserService} for event dispatching.
 * - {@see PasswordChanged} for the event emitted.
 */
#[AsEventListener(event: PasswordChanged::class)]
class PasswordChangedHandler
{
    private NotificationService $notificationService;

    /**
     * Constructor.
     *
     * @param NotificationService $notificationService Service for notifying users.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Invoked when PasswordChanged event is dispatched. Sends a notification.
     *
     * @param PasswordChanged $event The PasswordChanged event instance.
     * @return void
     */
    public function __invoke(PasswordChanged $event): void
    {
        $userId = $event->getUserId();
        $this->notificationService->notifyPasswordChange($userId);
    }
}
