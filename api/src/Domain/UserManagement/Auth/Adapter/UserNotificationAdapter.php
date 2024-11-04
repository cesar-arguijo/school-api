<?php

namespace App\Infrastructure\Auth\Service;

use App\Domain\UserManagement\Auth\Service\UserNotificationInterface;
use App\Domain\UserManagement\Service\NotificationService;

/**
 * Adapter for NotificationService to handle notifications in Auth.
 */
class UserNotificationAdapter implements UserNotificationInterface
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function notifyAdmins(string $message): void
    {
        $this->notificationService->notifyAdmins($message);
    }
}