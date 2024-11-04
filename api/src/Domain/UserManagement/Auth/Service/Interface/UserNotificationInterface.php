<?php

namespace App\Domain\UserManagement\Auth\Service\Interface;

/**
 * Interface for notifying administrators of user role changes.
 */
interface UserNotificationInterface
{
    public function notifyAdmins(string $message): void;
}