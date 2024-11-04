<?php

namespace App\Domain\UserManagement\Service;

use App\Domain\UserManagement\Entity\User;
use App\Domain\UserManagement\Entity\Device;
use App\Infrastructure\Mailer\MailerService;

/**
 * Service responsible for sending security alerts and notifications.
 * Used to notify users of security-related events, such as login from a new device.
 */
class AlertService
{
    /**
     * @var MailerService The mailer service used to send email notifications.
     */
    private MailerService $mailerService;

    /**
     * Constructs the AlertService with a MailerService instance.
     *
     * @param MailerService $mailerService The mailer service.
     */
    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * Sends a security alert for a new device login.
     *
     * @param User $user The user to notify.
     * @param Device $device The device used for the login.
     * @return void
     */
    public function sendNewDeviceAlert(User $user, Device $device): void
    {
        $email = $user->getEmail();
        $subject = 'New Device Login Alert';
        $body = sprintf(
            "Dear %s,\n\nA new login was detected from a %s device (ID: %s). If this wasn't you, please reset your password immediately.",
            $user->getUsername(),
            $device->getDeviceType(),
            $device->getId() // Assuming Device has an ID or unique identifier
        );

        $this->mailerService->sendEmail($email, $subject, $body);
    }
}
